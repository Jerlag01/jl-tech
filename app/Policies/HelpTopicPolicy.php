<?php

namespace App\Policies;

class HelpTopicPolicy extends AreaPolicy
{
    protected function area(): string
    {
        return 'helpdesk';
    }
}
