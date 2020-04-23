<?php

namespace App\Conversations;

class NightConversation extends CoreConversation
{   
    public function run()
    {
        parent::run();
        $this->protocol = 'night-protocol';
        $this->askSymptomatics();
    }
}
