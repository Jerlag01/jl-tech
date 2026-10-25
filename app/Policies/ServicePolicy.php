<?php

namespace App\Policies;

class ServicePolicy extends AreaPolicy
{
    protected function area(): string
    {
        return 'services';
    }
}
