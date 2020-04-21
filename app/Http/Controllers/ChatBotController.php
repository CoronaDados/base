<?php

namespace App\Http\Controllers;

use App\Conversations\InitialConversation;
use App\Conversations\MorningConversation;
use App\Conversations\NightConversation;
use App\Conversations\RiskGroupConversation;
use App\Http\Controllers\Controller;

class ChatBotController extends Controller
{
    public function handle()
    {
        $botman = app('botman');
  
        $botman->hears('oi', function ($bot) {
            $bot->startConversation(new InitialConversation);
        });
        $botman->hears('bom dia', function ($bot) {
            $bot->startConversation(new MorningConversation);
        });
        $botman->hears('boa noite', function ($bot) {    
            $bot->startConversation(new NightConversation);
        });
        $botman->hears('grupo risco', function ($bot) {
            $bot->startConversation(new RiskGroupConversation);
        });
        $botman->fallback(function($bot) {
            $bot->reply('Desculpe, eu não entendi. Os protocolos de ativação são: "oi", "bom dia", "boa noite" e "grupo risco"');
        });
  
        $botman->listen();
    }
}
