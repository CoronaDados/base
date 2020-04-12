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
        $query = "SELECT p.id, p.name, p.email, c.name AS lider 
            FROM persons p
            INNER JOIN personables pp ON p.id = pp.person_id
            INNER JOIN company_users c ON pp.personable_id = c.id
            WHERE p.id IN ( 
                SELECT pp.person_id FROM personables pp WHERE personable_id IN ( 
                    SELECT c.id WHERE company_users WHERE company_id = " . $this->company()->first()->id . " ) )";

        return DB::select(DB::raw($query));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
