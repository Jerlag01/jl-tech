<?php

namespace App\Policies;

class DepartmentPolicy extends AreaPolicy
{
    protected function area(): string
    {
        return 'helpdesk';
    }
}
