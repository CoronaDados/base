<?php

namespace App\Model\Company;

use App\Model\Person\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{

    protected $fillable = [
        'razao', 'cnpj'
    ];

    public function users()
    {
        return $this->hasMany('App\Model\Company\CompanyUser');
    }

    public function getCountsDashboardRiskGroups()
    {
        $query = "SELECT rgp.name,
                        Count(*)                                        AS total_group,
                        Count(IF(status_covid = 'Suspeito', 1, NULL))   AS total_suspect,
                        Count(IF(status_covid = 'Negativo', 1, NULL))   AS total_negative,
                        Count(IF(status_covid = 'Positivo', 1, NULL))   AS total_positive,
                        Count(IF(status_covid = 'Recuperado', 1, NULL)) AS total_recover,
                        Count(IF(status_covid = 'Óbito', 1, NULL))     AS total_death
                FROM   risk_group_person rgp
                        JOIN company_users cu
                        ON cu.person_id = rgp.person_id
                        LEFT JOIN (SELECT rgp.name, status_covid, p.id
                                    FROM persons p
                                    JOIN (SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max ON (cp_max.person_id = p.id)
                                    JOIN cases_person cp ON cp.id = cp_max.max_id
                                    JOIN risk_group_person rgp on p.id = rgp.person_id
                                    GROUP BY rgp.name, status_covid, p.id
                        ) AS status ON status.name = rgp.name AND status.id = rgp.person_id
                WHERE  cu.company_id = ".$this->id."
                GROUP BY rgp.name";

        return DB::select(DB::raw($query));
    }
}


