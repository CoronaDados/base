<?php

namespace App\Model\Person;

use Illuminate\Database\Eloquent\Model;

class RiskGroupPerson extends Model
{
    protected $table = 'risk_group_person';

    protected $fillable = [
        'person_id',
        'name',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'id', 'person_id');
    }
}
