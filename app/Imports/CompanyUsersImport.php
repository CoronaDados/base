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

        $password = preg_replace('/[^0-9]/', '', $row['cpf']);
        $cpf = $this->removePunctuation($row['cpf']);
        $cpf_lider = $this->removePunctuation($row['cpf_lider']);
        $cep = $this->removePunctuation($row['cep']);
        $birthday = ($row['bithday'] !== null) ? Carbon::parse($row['bithday'])->format('Y-m-d') : null;

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

        $user = CompanyUser::firstOrCreate(
            ['person_id' => $person->id, 'company_id' => $this->importedBy->company_id],
            [
                'email' => $row['email'],
                'password' => Hash::make($password),
            ]
        );
        if ($user->email != $row['email']) {
            $user->email = $row['email'];
            $user->save();
        }

        $user->syncRoles($this->role);

        // pegar o person com o CPF igual o do lider
        // pegar o company_user referente ao person acima da mesma empresa
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

        if (!$userLider->persons()->where('person_id', $person->id)->exists()) {
            $userLider->persons()->save($person);
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
