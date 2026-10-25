<?php

namespace App\Policies;

class SlaPlanPolicy extends AreaPolicy
{
    protected function area(): string
    {
        return 'helpdesk';
    }
}
