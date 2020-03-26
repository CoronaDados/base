<?php

namespace App\Model\People;

use App\Model\Company\CompanyUser;
use DemeterChain\C;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class People extends Model
{
    protected $table = 'persons';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'cpf',
        'sector',
        'bithday',
        'gender',
        'risk_group',
        'status',
        'cep',
        'ibge',
        'state',
        'city',
        'neighborhood',
        'street',
        'complement',
        'more',
    ];

    public function companyUsers()
    {
        return $this->morphedByMany(CompanyUser::class, 'personable');
    }
    public function casePeopleDay()
    {
        return $this->hasOne(CasePeople::class,'person_id')->whereDay('created_at','>',Carbon::yesterday())    ->latest();
    }

    public function createCasePeopleDay()
    {
        return $this->hasMany(CasePeople::class,'person_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class,'person_id');
    }

}
