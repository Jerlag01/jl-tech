<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AdminNavigationGroup implements HasLabel
{
    case Content;
    case Contact;
    case Helpdesk;
    case System;

    public function getLabel(): string
    {
        return __('admin.groups.'.strtolower($this->name));
    }
}
