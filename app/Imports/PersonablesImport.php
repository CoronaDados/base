<?php

namespace App\Imports;

use App\Mail\Imports\importUsersErrorMail;
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

class PersonablesImport implements OnEachRow, WithHeadingRow, WithChunkReading, ShouldQueue, WithEvents, WithBatchInserts, SkipsOnFailure
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

        $person = Person::where('cpf', $cpf)->first();
        if (!$person) {
            return;
        }

        $personLider = Person::where('cpf', $cpf_lider)->first();
        if ($personLider) {
            $userLider = CompanyUser::where([
                'person_id' => $personLider->id,
                'company_id' => $this->importedBy->company_id
            ])->first();
        }

        if (!isset($userLider) || !$userLider) {
            $userLider = $this->importedBy;
        }

        $person->companyUsers()->sync($userLider);
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
