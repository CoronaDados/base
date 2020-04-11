<?php

namespace App\Imports;

use App\Model\People\People;
use App\Model\Company\CompanyUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
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
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PersonsImport implements OnEachRow, WithHeadingRow, WithChunkReading, ShouldQueue, WithEvents, WithBatchInserts, SkipsOnFailure
{

    use Importable ,SkipsFailures;

    private $companyID;

    public function __construct($companyID)
    {
        $this->companyID = $companyID;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                Log::error($event->getException()->getMessage());
            },
        ];
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        // removes all keys with ""
        $row = array_filter($row->toArray(),
            function ($k) {
                return $k != "";
            },
            ARRAY_FILTER_USE_KEY);

        $cpf = $this->removePunctuation($row['cpf']);
        $cpf_lider = $this->removePunctuation($row['cpf_lider']);
        $cep = $this->removePunctuation($row['cep']);
        $nascimento = ($row['nascimento'] !== null) ? Date::excelToDateTimeObject($row['nascimento'])->format('Y-m-d') : null;

        $people = People::firstOrCreate(
            ['cpf' => $cpf],
            [
                'name' => $row['nome'],
                'cpf' => $cpf,
                'email' => $row['email'],
                'street' => $row['logradouro'],
                'neighborhood' => $row['bairro'],
                'complement' => $row['complemento'],
                'cep' => $cep,
                'phone' => $row['telefone'],
                'city' => $row['municipio'],
//                'sector' => $row['sector'],
                'ibge' => $row['ibge'],
                'bithday' => $nascimento,
                'gender' => $row['genero'],
                'risk_group' => $row['grupo_risco'],
//                    'status' => $row['status'],
//                    'state' => $row['state'],
//                    'more' => $row['more'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $user = CompanyUser::query()->where([
            ['cpf', $cpf_lider],
            ['company_id', $this->companyID]
        ])->first();

        if($user) {
            $user->persons()->save($people);
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

    private function removePunctuation($string) {
        return preg_replace('/[^0-9]/', '', $string);
    }
}
