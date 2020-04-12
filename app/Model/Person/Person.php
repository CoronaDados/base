<?php

namespace App\Model\Person;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Person extends Model
{
    protected $table = 'persons';

    protected $fillable = [
        'name',
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
    public function casePersonDay()
    {
        return $this->hasOne(CasePerson::class, 'person_id')->whereDay('created_at', '=', Carbon::today())->latest();
    }

    public function createCasePersonDay()
    {
        return $this->hasMany(CasePerson::class, 'person_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'person_id');
    }
}
