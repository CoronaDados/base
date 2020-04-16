<?php

namespace App\Imports;

use App\Mail\ImportUsersErrorMail;
use App\Model\Company\CompanyUser;
use App\Model\Person\Person;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
                Mail::to(config('app.email_list_error'))->send(new ImportUsersErrorMail($this->importedBy, $event));
            },
        ];
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();

        $row = array_filter(
            $row->toArray(),
            function ($k) {
                return $k != "";
            },
            ARRAY_FILTER_USE_KEY
        );

        $cpf = $this->removePunctuation($row['cpf']);
        $cpf_lider = $this->removePunctuation($row['cpf_lider']);
        $cep = $this->removePunctuation($row['cep']);
        $birthday = ($row['birthday'] !== null) ? Carbon::parse($row['birthday'])->format('Y-m-d') : null;
        $email = $row['email'];

        if ($email == '' || $cpf == '') {
            return;
        }

        $person = Person::updateOrCreate(
            ['cpf' => $cpf],
            [
                'name' => $row['name'],
                'cpf' => $cpf,
                'street' => $row['street'],
                'neighborhood' => $row['neighborhood'],
                'complement' => $row['complement'],
                'cep' => $cep,
                'phone' => $row['phone'],
                'city' => $row['city'],
                'sector' => null,
                'ibge' => $row['ibge'],
                'birthday' => $birthday,
                'gender' => $row['gender'],
                'risk_group' => $row['risk_group'],
                'status' => $row['status'],
                'state' => $row['state'],
                'number' => $row['number'],
            ]
        );

        $user = CompanyUser::firstOrCreate(
            ['person_id' => $person->id, 'company_id' => $this->importedBy->company_id],
            [
                'email' => $email,
                'password' => Hash::make($cpf),
                'email_verified_at' => now(),
                'force_new_password' => true,
            ]
        );
        $user->email = $email;
        $user->save();

        $user->assignRole($this->role);
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
