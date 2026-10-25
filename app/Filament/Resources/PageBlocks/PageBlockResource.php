<?php

namespace App\Filament\Resources\PageBlocks;

use App\Enums\AdminNavigationGroup;
use App\Filament\Resources\PageBlocks\Pages\CreatePageBlock;
use App\Filament\Resources\PageBlocks\Pages\EditPageBlock;
use App\Filament\Resources\PageBlocks\Pages\ListPageBlocks;
use App\Filament\Resources\PageBlocks\Schemas\PageBlockForm;
use App\Filament\Resources\PageBlocks\Tables\PageBlocksTable;
use App\Models\Item;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class PageBlockResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = AdminNavigationGroup::Content;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'page-blocks';

    public static function getModelLabel(): string
    {
        return __('admin.resources.page_block.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.page_block.plural');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        return $record?->tr('title');
    }

    public static function form(Schema $schema): Schema
    {
        return PageBlockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageBlocksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPageBlocks::route('/'),
            'create' => CreatePageBlock::route('/create'),
            'edit' => EditPageBlock::route('/{record}/edit'),
        ];
    }
}
