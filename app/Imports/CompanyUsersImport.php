<?php

namespace App\Imports;

use App\Model\Company\CompanyUser;
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

    use Importable ,SkipsFailures;

    public function __construct(CompanyUser $importedBy, $role)
    {
//        set_time_limit(500000);
        $this->importedBy = $importedBy;
        $this->role = $role;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
            Log::error($event);
             //   $this->importedBy->notify(new ImportHasFailedNotification);
            },
        ];
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        if(Str::length($row['name']) > 1 && Str::length($row['email']) > 1 && Str::length($row['cpf']) > 1) {

            $password = preg_replace('/[^0-9]/', '', $row['cpf']);

            $user = CompanyUser::firstOrCreate(
                ['cpf' => $row['cpf'], 'company_id' => $this->importedBy->company_id],
                [
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'cpf' => $row['cpf'],
                    'phone' => $row['phone'],
                    'is_admin' => false,
                    'password' => Hash::make($password),
                ]);
            $user->assignRole($this->role);
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
