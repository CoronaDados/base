<?php

namespace App\Model\People;

use App\Model\Company\CompanyUser;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $table = 'persons';


    public function CompanyUsers()
    {
        return $this->morphMany(CompanyUser::class, 'personables');
    }
}
