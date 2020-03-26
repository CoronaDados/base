<?php

namespace App\Imports;

use App\Model\Company\CompanyUser;
use App\Model\People\People;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PersonsImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{

    public function __construct()
    {
        set_time_limit(500000);
    }


    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $people = People::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'cpf' => $row['cpf'],
                'cep' => $row['cep'],
            ]);
            $user = CompanyUser::query()->where('name','=', $row['name_lider'])->with('persons')->first();
            if($user) {
                $user->persons()->save($people);
            }
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
