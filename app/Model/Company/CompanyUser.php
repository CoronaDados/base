<?php

namespace App\Model\Company;

use App\Model\People\People;
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
        'name', 'email', 'is_admin', 'password','email_verfied_at', 'phone', 'cpf', 'department', 'company_id'
    ];

    protected $hidden = ['password'];


    public function persons()
    {
        return $this->morphToMany(People::class, 'personable','personables','personable_id', 'person_id')->orderByDesc('created_at');
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function countPersons()
    {
        return DB::select(DB::raw("select count(*) as total from persons where id IN ( select person_id from personables where personable_id in ( select id from company_users where company_id = ".$this->company()->first()->id." ) )"));
    }

    public function personsInCompany()
    {
        return DB::select(DB::raw("select
		p.id, p.name, p.email,
        c.id,
        c.name as lider from persons p
INNER JOIN personables pp
ON p.id = pp.person_id 
INNER JOIN company_users c
ON pp.personable_id = c.id
where p.id IN ( select pp.person_id from personables pp where personable_id in ( select c.id  company_users where company_id = ".$this->company()->first()->id." ) )"));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
