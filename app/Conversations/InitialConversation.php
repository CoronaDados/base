<?php

namespace App\Conversations;

class InitialConversation extends CoreConversation
{   
    public function run()
    {
        parent::run();
        $this->protocol = 'initial-protocol';
        $this->askInitialContact();
    }
}
