<?php

namespace App\Imports;

use App\Model\Company\CompanyUser;
use App\Model\People\People;
use App\Notifications\Imports\ImportHasFailedNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;

class CompanyUsersImport implements OnEachRow, WithHeadingRow, WithChunkReading, ShouldQueue, WithEvents, WithBatchInserts, SkipsOnFailure
{

    use Importable, SkipsFailures;

    public function __construct(CompanyUser $importedBy, $role)
    {
        $this->importedBy = $importedBy;
        $this->role = $role;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                $this->importedBy->notify(new ImportHasFailedNotification($event));
            },
        ];
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();

        if (Str::length($row['name']) > 1 && Str::length($row['email']) > 1 && Str::length($row['cpf']) > 1) {

            $password = preg_replace('/[^0-9]/', '', $row['cpf']);
            $cpf = $this->removePunctuation($row['cpf']);
            $cpf_lider = $this->removePunctuation($row['cpf_lider']);
            $cep = $this->removePunctuation($row['cep']);
            $birthday = ($row['bithday'] !== null) ? Carbon::parse($row['bithday'])->format('Y-m-d') : null;

            $user = CompanyUser::firstOrCreate(
                ['cpf' => $row['cpf'], 'company_id' => $this->importedBy->company_id],
                [
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'cpf' => $row['cpf'],
                    'phone' => $row['phone'],
                    'is_admin' => false,
                    'password' => Hash::make($password),
                ]
            );
            $user->assignRole($this->role);

            $people = People::firstOrCreate(
                ['cpf' => $cpf],
                [
                    'name' => $row['name'],
                    'cpf' => $cpf,
                    'email' => $row['email'],
                    'street' => $row['street'],
                    'neighborhood' => $row['neighborhood'],
                    'complement' => $row['complement'],
                    'cep' => $cep,
                    'phone' => $row['phone'],
                    'city' => $row['city'],
                    'sector' => $row['sector'],
                    'ibge' => $row['ibge'],
                    'bithday' => $birthday,
                    'gender' => $row['gender'],
                    'risk_group' => $row['risk_group'],
                    'status' => $row['status'],
                    'state' => $row['state'],
                    'number' => $row['number'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $lider = CompanyUser::query()->where([
                ['cpf', $cpf_lider],
                [
                    'company_id', $this->importedBy->company_id,
                ]
            ])->first();

            if (!$lider) {
                $lider = $this->importedBy;
            }

            if (!$lider->persons()->where('person_id', $people->id)->exists()) {
                $lider->persons()->save($people);
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    private function removePunctuation($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }
}
