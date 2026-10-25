<?php

namespace App\Policies;

class ItemPolicy extends AreaPolicy
{
    protected function area(): string
    {
        return 'page_blocks';
    }
}
