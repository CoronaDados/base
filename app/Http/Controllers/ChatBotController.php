<?php

namespace App\Http\Controllers;

use App\Conversations\MorningConversation;
use App\Http\Controllers\Controller;

class ChatBotController extends Controller
{
    public function handle()
    {
        $botman = app('botman');
        
        $botman->hears('', function ($bot) {
            $bot->startConversation(new MorningConversation);
        });        
        $botman->listen();
    }
}
