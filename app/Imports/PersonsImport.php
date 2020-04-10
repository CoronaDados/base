<?php

namespace App\Imports;

use App\Model\People\People;
use App\Model\Company\CompanyUser;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        $row      = $row->toArray();

        if(Str::length($row['name']) > 1 && Str::length($row['email']) > 1 && Str::length($row['cpf']) > 1) {

            $cpf = preg_replace('/[^0-9]/', '', $row['cpf']);
            $cpf_lider = preg_replace('/[^0-9]/', '', $row['cpf_lider']);

            $people = People::firstOrCreate(
                ['cpf' => $cpf],
                [
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'cpf' => $cpf,
                    'sector' => $row['sector'],
                    'bithday' => $row['bithday'],
                    'gender' => $row['gender'],
                    'risk_group' => $row['risk_group'],
                    'status' => $row['status'],
                    'cep' => $row['cep'],
                    'ibge' => $row['ibge'],
                    'state' => $row['state'],
                    'city' => $row['city'],
                    'neighborhood' => $row['neighborhood'],
                    'street' => $row['street'],
                    'complement' => $row['complement'],
                    'more' => $row['more'],
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
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
