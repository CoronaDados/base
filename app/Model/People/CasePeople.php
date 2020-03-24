<?php

namespace App\Model\People;

use Illuminate\Database\Eloquent\Model;

class CasePeople extends Model
{
    protected $table = 'cases_person';

    protected $fillable = [
        'status',
    ];
}
