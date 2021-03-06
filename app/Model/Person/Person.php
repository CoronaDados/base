<?php

namespace App\Model\Person;

use App\Model\Company\CompanyUser;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Wildside\Userstamps\Userstamps;

class Person extends Model
{
    use Userstamps;

    protected $table = 'persons';

    protected $fillable = [
        'name',
        'phone',
        'cpf',
        'sector',
        'birthday',
        'gender',
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
        'bot_optin'
    ];

    protected $casts = [
        'birthday' => 'date'
    ];

    public function companyUsers()
    {
        return $this->morphedByMany(CompanyUser::class, 'personable');
    }

    public function riskGroups()
    {
        return $this->hasMany(RiskGroupPerson::class, 'person_id');
    }

    public function monitoringPersonToday()
    {
        return $this->monitoringsPerson()->whereDay('created_at', '=', Carbon::today())->first();
    }

    public function monitoringsPerson()
    {
        return $this->hasMany(MonitoringPerson::class, 'person_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'person_id');
    }

    public function getBirthdayFormattedAttribute()
    {
        return $this->birthday ? $this->birthday->format('d/m/Y') : null;
    }

    public function casesPerson()
    {
        return $this->hasMany(CasePerson::class, 'person_id')->orderBy('created_at', 'desc');
    }
}
