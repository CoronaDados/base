<?php

namespace App\Model\Company;

use App\Enums\StatusCovidType;
use App\Model\Person\CasePerson;
use App\Model\Person\Person;
use App\Notifications\Company\ResetPasswordNotification;
use App\Notifications\Company\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User AS Authenticatable;
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
        return DB::select(DB::raw("select count(*) AS total from persons where id IN ( select person_id from personables where personable_id in ( select id from company_users where company_id = " . $this->company()->first()->id . " ) )"))[0]->total;
    }

    public function personsInCompany($options = [])
    {
        $companyUserId = $this->id;

        $query = "SELECT c_person.id, p.name, c_person.email, p.sector, l.name AS lider
            FROM persons p
            INNER JOIN company_users c_person ON c_person.person_id = p.id
            INNER JOIN personables pp ON p.id = pp.person_id
            INNER JOIN company_users c ON pp.personable_id = c.id
            INNER JOIN persons l ON l.id = c.person_id
            WHERE p.id IN (
                SELECT pp.person_id FROM personables pp WHERE personable_id IN (
                    SELECT id FROM company_users WHERE company_id = " . $this->company()->first()->id . " ) )";

        if (in_array("byLeader", $options)) {
            $query .=  "AND c.id = " . $companyUserId;
        }

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

    public function monitoringPersons($options = []) {
        $companyUserId = $this->id;

        $query = DB::table('persons', 'p')
            ->select(DB::raw(' c_person.id AS person_id, p.name,c_person.email, p.phone'))
            ->join('company_users AS c_person', 'c_person.person_id', '=', 'p.id')
            ->join('personables AS pp', 'p.id', '=', 'pp.person_id')
            ->join('company_users AS c', 'pp.personable_id', '=', 'c.id')
            ->whereRaw('p.id IN ( SELECT pp.person_id FROM personables pp WHERE
                personable_id IN ( SELECT id FROM company_users WHERE company_id = '. $this->company_id .' ) )')
            ->whereRaw('p.id NOT IN ( SELECT mp.person_id FROM monitoring_person mp WHERE DATE(mp.created_at) >= CURDATE() )');

        if (in_array("byLeader", $options, true)) {
            $query->where('c.id', $companyUserId);
        }

        return $query->get();
    }

    public function monitoringsHistoryPerson($options = [])
    {
        $companyUserId = $this->id;

        $query = DB::table('persons', 'p')
            ->select(DB::raw(' c_person.id AS person_id, p.name, mp.created_at AS dateMonitoring, mp.symptoms, cp.status_covid'))
            ->join('monitoring_person AS mp', 'p.id', '=', 'mp.person_id')
            ->leftJoin(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
            ->leftJoin('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
            ->join('company_users AS c_person', 'c_person.person_id', '=', 'p.id')
            ->join('personables AS pp', 'p.id', '=', 'pp.person_id')
            ->join('company_users AS c', 'pp.personable_id', '=', 'c.id')
            ->whereRaw('p.id IN ( SELECT pp.person_id FROM personables pp WHERE
                personable_id IN ( SELECT id FROM company_users WHERE company_id = '. $this->company_id .' ) )');

        if (in_array("byLeader", $options, true)) {
            $query->where('c.id', $companyUserId);
        }

        $query->orderBy('mp.created_at', 'desc');

        return $query->get();
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
            ->select(DB::raw('count(*) AS total'))
            ->join('company_users AS cu', 'cu.id', '=', 'mp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->whereRaw('DATE(mp.created_at) = CURRENT_DATE()')
            ->first()->total;
    }

    public function countMyPersonsMonitoredToday()
    {
        return DB::table('monitoring_person', 'mp')
            ->select(DB::raw('count(*) AS total'))
            ->where('mp.user_id', $this->id)
            ->whereRaw('DATE(mp.created_at) = CURRENT_DATE()')
            ->first()->total;
    }

    public function countAllConfirmedCases()
    {
        return DB::table('cases_person', 'cp')
            ->select(DB::raw('count(*) AS total'))
            ->join('company_users AS cu', 'cu.id', '=', 'cp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->where('cp.status_covid', StatusCovidType::POSITIVO)
            ->first()->total;
    }

    public function countActivedConfirmedCases()
    {
        return DB::table('persons', 'p')
            ->select(DB::raw('count(status_covid) AS total'))
            ->join(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
            ->join('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
            ->join('company_users AS cu', 'cu.id', '=', 'cp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->where('cp.status_covid', StatusCovidType::POSITIVO)
            ->first()->total;
    }

    public function countConfirmedCasesToday()
    {
        return DB::table('persons', 'p')
            ->select(DB::raw('count(status_covid) AS total'))
            ->join(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
            ->join('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
            ->join('company_users AS cu', 'cu.id', '=', 'cp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->where('cp.status_covid', StatusCovidType::POSITIVO)
            ->whereRaw('DATE(cp.created_at) = CURRENT_DATE()')
            ->first()->total;
    }

    public function countConfirmedCasesYesterday()
    {
        return DB::table('persons', 'p')
            ->select(DB::raw('count(status_covid) AS total'))
            ->join(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
            ->join('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
            ->join('company_users AS cu', 'cu.id', '=', 'cp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->where('cp.status_covid', StatusCovidType::POSITIVO)
            ->whereRaw('DATE(cp.created_at) = DATE(NOW() - INTERVAL 1 DAY)')
            ->first()->total;
    }

    public function countAllRecoveredCases()
    {
        return DB::table('persons', 'p')
            ->select(DB::raw('count(status_covid) AS total'))
            ->join(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
            ->join('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
            ->join('company_users AS cu', 'cu.id', '=', 'cp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->where('cp.status_covid', StatusCovidType::RECUPERADO)
            ->first()->total;
    }

    public function countSuspiciousCases()
    {
        return DB::table('persons', 'p')
            ->select(DB::raw('count(status_covid) AS total'))
            ->join(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
            ->join('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
            ->join('company_users AS cu', 'cu.id', '=', 'cp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->where('cp.status_covid', StatusCovidType::SUSPEITO)
            ->first()->total;
    }

    public function countDeathCases()
    {
        return DB::table('persons', 'p')
            ->select(DB::raw('count(status_covid) AS total'))
            ->join(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
            ->join('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
            ->join('company_users AS cu', 'cu.id', '=', 'cp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->where('cp.status_covid', StatusCovidType::OBITO)
            ->first()->total;
    }

    public function personsActivedConfirmedCases()
    {
        return DB::table('persons', 'p')
            ->select(DB::raw('p.name, CONCAT(\'[\', GROUP_CONCAT(\'"\', rgp.name, \'"\'), \']\') AS riskGroups, cp.created_at AS date'))
            ->join(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
            ->join('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
            ->join('risk_group_person AS rgp', 'rgp.person_id', '=', 'p.id')
            ->join('company_users AS cu', 'cu.id', '=', 'cp.user_id')
            ->where('cu.company_id', $this->company_id)
            ->where('cp.status_covid', StatusCovidType::POSITIVO)
            ->groupBy('p.name', 'cp.created_at')
            ->get();
    }


    public function personsSuspiciousCases()
    {
        return DB::table('persons', 'p')
            ->select(DB::raw('p.name, l.name AS leader, cp.created_at AS date'))
            ->join(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
            ->join('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
            ->join('company_users AS cu', 'cu.id', '=', 'cp.user_id')
            ->join('personables AS pp', 'p.id', '=','pp.person_id')
            ->join('company_users AS c_leader', 'pp.personable_id', '=','c_leader.id')
            ->join('persons AS l', 'l.id', '=','c_leader.person_id')
            ->where('cu.company_id', $this->company_id)
            ->where('cp.status_covid', StatusCovidType::SUSPEITO)
            ->get();
    }

    public function casesPersonCreator()
    {
        return $this->hasMany(CasePerson::class, 'user_id');
    }
}
