<?php

namespace App\Model\Company;

use App\Model\People\People;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


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
        return $this->morphedByMany(People::class, 'personable', 'personables', 'person_id', 'personable_id');
    }

}
