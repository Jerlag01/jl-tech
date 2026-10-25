<?php

namespace App\Filament\Resources\PageBlocks\Pages;

use App\Filament\Resources\PageBlocks\PageBlockResource;
use App\Models\Item;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPageBlocks extends ListRecords
{
    protected static string $resource = PageBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn (): string => PageBlockResource::getUrl('create', array_filter(['group' => $this->activeTab !== 'all' ? $this->activeTab : null]))),
        ];
    }

    /**
     * One tab per section (timeline, leadership, fleet, ...).
     *
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $counts = Item::query()->toBase()->select('group')->selectRaw('count(*) as total')->groupBy('group')->pluck('total', 'group');

        return [
            'all' => Tab::make(__('admin.page_blocks.all')),
            ...collect(config('site.item_groups'))
                ->map(fn (array $group, string $name): Tab => Tab::make($group['label'])
                    ->badge($counts[$name] ?? 0)
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('group', $name)))
                ->all(),
        ];
    }
}
