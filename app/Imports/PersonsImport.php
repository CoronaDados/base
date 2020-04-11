<?php

namespace App\Imports;

use App\Mail\Imports\importUsersErrorMail;
use App\Model\People\People;
use App\Model\Company\CompanyUser;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
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

class PersonsImport implements OnEachRow, WithHeadingRow, WithChunkReading, ShouldQueue, WithEvents, WithBatchInserts, SkipsOnFailure
{

    use Importable, SkipsFailures;

    public function __construct(CompanyUser $importedBy)
    {
        $this->importedBy = $importedBy;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                Mail::to('willian.maria@sc.senai.br')
                    ->cc('douglas.baptista@sc.senai.br')
                    ->send(new importUsersErrorMail($this->importedBy, $event));
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
        $birthday = ($row['bithday'] !== null) ? Carbon::parse($row['bithday'])->format('Y-m-d') : null;

        $people = People::updateOrCreate(
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

    public function chunkSize(): int
    {
        return 10000;
    }

    public function batchSize(): int
    {
        return 10000;
    }

    private function removePunctuation($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }
}
