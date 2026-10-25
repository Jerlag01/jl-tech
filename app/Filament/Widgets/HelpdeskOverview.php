<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HelpdeskOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('helpdesk.view') ?? false;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(__('helpdesk.stats.open_tickets'), Ticket::active()->count())
                ->description(__('helpdesk.stats.overdue', ['count' => Ticket::overdue()->count()]))
                ->descriptionColor(Ticket::overdue()->exists() ? 'danger' : 'gray')
                ->icon(Heroicon::OutlinedLifebuoy),
            Stat::make(__('helpdesk.stats.created_this_month'), Ticket::where('created_at', '>=', now()->startOfMonth())->count())
                ->icon(Heroicon::OutlinedCalendar),
            Stat::make(__('helpdesk.resources.agent.plural'), User::staff()->count())
                ->description(__('helpdesk.stats.departments', ['count' => Department::count()]))
                ->icon(Heroicon::OutlinedIdentification),
            Stat::make(__('helpdesk.resources.client.plural'), User::where('support_role', UserRole::Customer)->count())
                ->icon(Heroicon::OutlinedUsers),
        ];
    }
}
