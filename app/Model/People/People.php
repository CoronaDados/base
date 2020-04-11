<?php

namespace App\Model\People;

use App\Model\Company\CompanyUser;
use Vinkla\Hashids\Facades\Hashids;
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
        'number',
        'more',
    ];

    public function getCodeAttribute()
    {
        return Hashids::encode($this->id);
    }

    public function companyUsers()
    {
        return $this->morphTo('personable', 'personable_type', 'personable_id');
    }
    public function casePeopleDay()
    {
        return $this->hasOne(CasePeople::class, 'person_id')->whereDay('created_at', '=', Carbon::today())->latest();
    }

    public function createCasePeopleDay()
    {
        return $this->hasMany(CasePeople::class, 'person_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'person_id');
    }
}
