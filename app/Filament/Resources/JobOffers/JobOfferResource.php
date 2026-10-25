<?php

namespace App\Filament\Resources\JobOffers;

use App\Enums\AdminNavigationGroup;
use App\Filament\Resources\JobOffers\Pages\CreateJobOffer;
use App\Filament\Resources\JobOffers\Pages\EditJobOffer;
use App\Filament\Resources\JobOffers\Pages\ListJobOffers;
use App\Filament\Resources\JobOffers\Schemas\JobOfferForm;
use App\Filament\Resources\JobOffers\Tables\JobOffersTable;
use App\Models\JobOffer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class JobOfferResource extends Resource
{
    protected static ?string $model = JobOffer::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = AdminNavigationGroup::Content;

    protected static ?int $navigationSort = 8;

    protected static ?string $slug = 'vacancies';

    public static function getModelLabel(): string
    {
        return __('admin.resources.job_offer.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.job_offer.plural');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        return $record?->tr('title');
    }

    public static function form(Schema $schema): Schema
    {
        return JobOfferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobOffersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobOffers::route('/'),
            'create' => CreateJobOffer::route('/create'),
            'edit' => EditJobOffer::route('/{record}/edit'),
        ];
    }
}
