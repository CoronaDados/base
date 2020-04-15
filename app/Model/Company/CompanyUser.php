<?php

namespace App\Model\Company;

use App\Model\Person\Person;
use App\Notifications\Company\ResetPasswordNotification;
use App\Notifications\Company\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Spatie\Permission\Traits\HasRoles;


class CompanyUser  extends Authenticatable implements MustVerifyEmail
{
    use Notifiable,
        HasRoles;

    protected $guard = 'company';

    protected $fillable = [
        'email', 'is_admin', 'password', 'email_verified_at', 'company_id', 'person_id', 'force_new_password'
    ];

    protected $hidden = ['password'];


    public function persons()
    {
        return $this->morphToMany(Person::class, 'personable', 'personables', 'personable_id', 'person_id')->orderByDesc('personables.created_at');
    }

    public function monitoringsPerson()
    {
        $query = "SELECT mp.created_at, p.name, mp.symptoms, l.name AS leader
            FROM monitoring_person mp
            INNER JOIN persons p ON p.id = mp.person_id
            INNER JOIN company_users c_person ON c_person.person_id = p.id
            INNER JOIN personables pp ON p.id = pp.person_id
            INNER JOIN company_users c ON pp.personable_id = c.id
            INNER JOIN persons l ON l.id = c.person_id
            WHERE p.id IN
            (
                SELECT pp.person_id FROM personables pp WHERE personable_id IN
                (
                    SELECT id FROM company_users WHERE company_id = " . $this->company()->first()->id . "
                )
            )";

        return DB::select(DB::raw($query));
    }

    public function monitoringsPersonByLeader()
    {
        $companyUserId = $this->id;

        $query = "SELECT mp.created_at, p.name, mp.symptoms, l.name AS leader
            FROM monitoring_person mp
            INNER JOIN persons p ON p.id = mp.person_id
            INNER JOIN company_users c_person ON c_person.person_id = p.id
            INNER JOIN personables pp ON p.id = pp.person_id
            INNER JOIN company_users c ON pp.personable_id = c.id
            INNER JOIN persons l ON l.id = c.person_id
            WHERE p.id IN
            (
                SELECT pp.person_id FROM personables pp WHERE personable_id IN
                (
                    SELECT id FROM company_users WHERE company_id = " . $this->company()->first()->id . "
                )
            ) AND c.id = " . $companyUserId;

        return DB::select(DB::raw($query));
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
        return DB::select(DB::raw("select count(*) as total from persons where id IN ( select person_id from personables where personable_id in ( select id from company_users where company_id = " . $this->company()->first()->id . " ) )"))[0]->total;
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

    public function personsInCompanyByLeader()
    {
        $companyUserId = $this->id;

        $query = "SELECT c_person.id, p.name, c_person.email, l.name AS lider
            FROM persons p
            INNER JOIN company_users c_person ON c_person.person_id = p.id
            INNER JOIN personables pp ON p.id = pp.person_id
            INNER JOIN company_users c ON pp.personable_id = c.id
            INNER JOIN persons l ON l.id = c.person_id
            WHERE p.id IN
            (
                SELECT pp.person_id FROM personables pp WHERE personable_id IN
                (
                    SELECT id FROM company_users WHERE company_id = " . $this->company()->first()->id . "
                )
            ) AND c.id = " . $companyUserId;

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

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
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

    public function needChangePassword()
    {
        return $this->force_new_password == true;
    }

    public function countPersonsInCompanyMonitoredToday()
    {
        return DB::table('monitoring_person', 'mp')
            ->select(DB::raw('count(*) as total'))
            ->join('company_users as cu', 'cu.id', '=', 'mp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->whereRaw('DATE(mp.created_at) = CURRENT_DATE()')
            ->first()->total;
    }

    public function countMyPersonsMonitoredToday()
    {
        return DB::table('monitoring_person', 'mp')
            ->select(DB::raw('count(*) as total'))
            ->where('mp.user_id', $this->id)
            ->whereRaw('DATE(mp.created_at) = CURRENT_DATE()')
            ->first()->total;
    }
}
