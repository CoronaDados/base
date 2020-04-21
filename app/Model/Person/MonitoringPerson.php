<?php

namespace App\Model\Person;

use App\Model\Company\CompanyUser;
use Illuminate\Database\Eloquent\Model;

class MonitoringPerson extends Model
{
    protected $table = 'monitoring_person';

    protected $fillable = [
        'person_id',
        'symptoms',
        'notes',
        'user_id',
        'type_user',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
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

    public function getSymptomsAttribute()
    {
        return $this->attributes['symptoms'] ? json_decode($this->attributes['symptoms'])->monitored : [];
    }
}
