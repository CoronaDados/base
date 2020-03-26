<?php

namespace App\Imports;

use App\Model\Company\CompanyUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CompanyUsersImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{

    public function __construct()
    {
        set_time_limit(500000);
    }


    /**
    * @param array $row
    *
    * @return CompanyUser
    */
    public function model(array $row)
    {
        return new CompanyUser([
            'company_id' => auth('company')->user()->company_id,
            'name' => $row['name'],
            'email' => $row['email'],
            'cpf' => $row['cpf'],
            'phone' => $row['phone'],
            'is_admin' => false,
            'password' => Hash::make($row['cpf']),
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
