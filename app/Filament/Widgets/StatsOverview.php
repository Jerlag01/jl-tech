<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BlockedIps\BlockedIpResource;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\BlockedIp;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        $user = auth()->user();

        return $user->can('viewAny', ContactMessage::class)
            || $user->can('viewAny', Post::class)
            || $user->can('viewAny', Project::class)
            || $user->can('viewAny', BlockedIp::class);
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        if ($user->can('viewAny', ContactMessage::class)) {
            $unread = ContactMessage::query()->unread()->count();
            $stats[] = Stat::make(__('admin.widgets.unread_messages'), $unread)
                ->icon(Heroicon::OutlinedInbox)
                ->color($unread > 0 ? 'primary' : 'gray')
                ->url(ContactMessageResource::getUrl('index'));
        }

        if ($user->can('viewAny', Post::class)) {
            $stats[] = Stat::make(__('admin.widgets.published_posts'), Post::query()->published()->count())
                ->icon(Heroicon::OutlinedNewspaper)
                ->url(PostResource::getUrl('index'));
        }

        if ($user->can('viewAny', Project::class)) {
            $stats[] = Stat::make(__('admin.widgets.published_projects'), Project::query()->where('active', true)->count())
                ->icon(Heroicon::OutlinedBuildingOffice2)
                ->url(ProjectResource::getUrl('index'));
        }

        if ($user->can('viewAny', BlockedIp::class)) {
            $stats[] = Stat::make(__('admin.widgets.blocked_ips'), BlockedIp::query()->where('is_active', true)->count())
                ->icon(Heroicon::OutlinedNoSymbol)
                ->url(BlockedIpResource::getUrl('index'));
        }

        return $stats;
    }
}
