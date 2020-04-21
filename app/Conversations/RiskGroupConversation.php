<?php

namespace App\Conversations;

class RiskGroupConversation extends CoreConversation
{   
    public function run()
    {
        parent::run();
        $this->protocol = 'risk-group-protocol';
        $this->askComplementRiskGroup();
    }
}
