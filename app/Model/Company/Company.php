<?php

namespace App\Model\Company;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $fillable = [
        'razao', 'cnpj'
    ];


    public function users()
    {
        return $this->hasMany('App\Model\Company\CompanyUser','company_id','id');
    }
}