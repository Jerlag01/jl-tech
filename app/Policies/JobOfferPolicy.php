<?php

namespace App\Policies;

class JobOfferPolicy extends AreaPolicy
{
    protected function area(): string
    {
        return 'job_offers';
    }
}
