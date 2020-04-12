<?php

namespace App\Model\Person;

use Illuminate\Database\Eloquent\Model;

class CasePerson extends Model
{
    protected $table = 'cases_person';

    protected $fillable = [
        'status',
        'user_id',
        'type_user'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->user_id = auth()->id();
            $model->user_type = get_class(auth()->user());
        });
    }
}
