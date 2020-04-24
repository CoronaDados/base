<?php

namespace App\Model\Company;

use App\Enums\ApplicationType;
use App\Model\Person\Person;
use Carbon\Carbon;
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
        $getLastCasesByPerson = DB::table('persons', 'p')
        ->select('rgp.name','status_covid','p.id')
        ->join(DB::raw('(SELECT MAX(id) max_id, person_id FROM cases_person GROUP BY person_id) cp_max'),'cp_max.person_id','=','p.id')
        ->join('cases_person AS cp', 'cp.id', '=', 'cp_max.max_id')
        ->join('risk_group_person AS rgp', 'p.id', '=', 'rgp.person_id')
        ->groupBy('rgp.name', 'status_covid', 'p.id');

        return DB::table('risk_group_person', 'rgp')
            ->select(DB::raw('rgp.name,
                        Count(*)                                          AS total_group,
                        Count(IF(status_covid = \'Suspeito\', 1, NULL))   AS total_suspect,
                        Count(IF(status_covid = \'Negativo\', 1, NULL))   AS total_negative,
                        Count(IF(status_covid = \'Positivo\', 1, NULL))   AS total_positive,
                        Count(IF(status_covid = \'Recuperado\', 1, NULL)) AS total_recover,
                        Count(IF(status_covid = \'Óbito\', 1, NULL))      AS total_death'))
            ->join('company_users AS cu', 'cu.person_id', '=', 'rgp.person_id')
            ->leftJoinSub($getLastCasesByPerson, 'lc', function($join) {
                $join->on('lc.name', '=',  'rgp.name')
                    ->on('lc.id', '=', 'rgp.person_id');
            })
            ->where('cu.company_id', $this->id)
            ->groupBy('rgp.name')->get();
    }

    public function getPersonsBotWhatsApp() 
    {

        $latestWhatsappMonitorings = DB::table('monitoring_person')
                   ->select('person_id', DB::raw('MAX(created_at) as last_date'))
                   ->where('application', ApplicationType::WHATSAPP)
                   ->groupBy('person_id');

        return DB::table('persons', 'p')
            ->select(DB::raw('p.name, p.phone, lm.last_date'))
            ->join('company_users AS cu', 'p.id', '=', 'cu.person_id')
            ->joinSub($latestWhatsappMonitorings, 'lm', function($join) {
                $join->on('p.id', '=', 'lm.person_id');
            })
            ->where('cu.company_id', $this->id)
            ->whereDate('lm.last_date', '<', Carbon::now()->format('Y-m-d'))
            ->whereNotNull('p.phone')
            ->get();
    }
}
