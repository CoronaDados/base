<?php

namespace App\Model\Person;

use Illuminate\Database\Eloquent\Model;

class CasePerson extends Model
{
    protected $table = 'cases_person';

    protected $fillable = [
        'status',
        'user_id',
        'type_user',
        'created_at'
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
        return $this->hasOne(Person::class, 'id', 'person_id');
    }

    public function leader()
    {
        return $this->hasOne(Person::class, 'id', 'user_id');
    }
}
