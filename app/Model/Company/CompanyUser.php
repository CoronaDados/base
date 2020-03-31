<?php

namespace App\Model\Company;

use App\Model\People\People;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;


class CompanyUser  extends Authenticatable
{
    use Notifiable;

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

}
