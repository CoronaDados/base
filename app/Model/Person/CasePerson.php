<?php

namespace App\Model\Person;

use App\Model\Company\CompanyUser;
use Illuminate\Database\Eloquent\Model;

class CasePerson extends Model
{
    protected $table = 'cases_person';

    protected $fillable = [
        'person_id',
        'status_covid',
        'status_test',
        'notes',
        'user_id',
        'type_user',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->user_id = auth()->id();
            $model->user_type = get_class(auth()->user());
        });
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function creator()
    {
        return $this->belongsTo(CompanyUser::class, 'user_id');
    }
}
