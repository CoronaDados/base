<?php

namespace App\Jobs;

use App\Model\Company\Company;
use App\Model\Person\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppMonitoringJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $companies = Company::all();

        foreach ($companies as $company) {

            $personsCompany = $company->getPersonsBotWhatsApp();       
            dd($personsCompany);
            $personsCompany = Person::where('cpf', '07805698902')->get();

            foreach ($personsCompany as $person) {

                $number = str_replace('+', '', $person->phone);

                if (substr($number, 0, 2) != '55') {
                    $number = '55' . $number;
                }

                $message = 'Olá *'.$person->name.'*, bom dia! Aqui é do CoronaDados, podemos fazer seu monitoramento de hoje?';

                $response = Http::asJson()
                    ->retry(3, 500)
                    ->withBasicAuth('admin', 'qrc0d32020')
                    ->post('http://192.168.192.1:8081/send-message', [
                        'number' => $number,
                        'message' => $message
                    ]);

                Log::info($response);

                continue;
            }
        }
    }
}
