<?php

namespace App\Model\Company;

use App\Model\Person\Person;
use App\Notifications\Company\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Spatie\Permission\Traits\HasRoles;


class CompanyUser  extends Authenticatable
{
    use Notifiable,
        HasRoles;

    protected $guard = 'company';

    protected $fillable = [
        'email', 'is_admin', 'password', 'email_verfied_at', 'company_id', 'person_id'
    ];

    protected $hidden = ['password'];


    public function persons()
    {
        return $this->morphToMany(Person::class, 'personable', 'personables', 'personable_id', 'person_id')->orderByDesc('personables.created_at');
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function countPersons()
    {
        return DB::select(DB::raw("select count(*) as total from persons where id IN ( select person_id from personables where personable_id in ( select id from company_users where company_id = " . $this->company()->first()->id . " ) )"));
    }

    public function personsInCompany()
    {
        $query = "SELECT c_person.id, p.name, c_person.email, l.name AS lider
            FROM persons p
            INNER JOIN company_users c_person ON c_person.person_id = p.id
            INNER JOIN personables pp ON p.id = pp.person_id
            INNER JOIN company_users c ON pp.personable_id = c.id
            INNER JOIN persons l ON l.id = c.person_id
            WHERE p.id IN (
                SELECT pp.person_id FROM personables pp WHERE personable_id IN (
                    SELECT id FROM company_users WHERE company_id = " . $this->company()->first()->id . " ) )";

        return DB::select(DB::raw($query));
    }

    public function leadersInCompany()
    {
        $query = "SELECT l.id, p.name FROM company_users l
        INNER JOIN persons p ON p.id = l.person_id
        INNER JOIN model_has_roles m ON m.model_id = l.id
        INNER JOIN roles r ON m.role_id = r.id
        WHERE l.company_id ="  . $this->company()->first()->id . " AND role_id IN (1,2)";

        return DB::select(DB::raw($query));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function leader()
    {
        $companyId = $this->company_id;
        return $this->person->companyUsers()->get()->first(function ($c, $key) use ($companyId) {
            return $c->company_id === $companyId;
        });
    }
}
