<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

class WhatsAppMonitoringJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $person;

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
    public function __construct(stdClass $person)
    {
        $this->person = $person;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $number = str_replace('+', '', $this->person->phone);

        if (substr($number, 0, 2) != '55') {
            $number = '55' . $number;
        }
        if (strlen($number) > 12) {
            $number = substr($number, 0, 4) . substr($number, 5);
        }

        $number = $number . '@c.us';

        $message = 'Olá *'.$this->person->name.'*, bom dia! Aqui é do CoronaDados, podemos fazer seu monitoramento de hoje?';

        $response = Http::asJson()
            ->retry(3, 500)
            ->withBasicAuth(config('app.coronadados_bot_user'), config('app.coronadados_bot_password'))
            ->post(config('app.coronadados_bot_url_send_message'), [
                'number' => $number,
                'message' => $message
            ])->throw();

        Log::info('Monitoramento WhatsApp: ' . $response);
    }
}
