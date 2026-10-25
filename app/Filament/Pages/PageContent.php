<?php

namespace App\Filament\Pages;

use App\Enums\AdminNavigationGroup;
use App\Filament\Support\ContentFields;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use UnitEnum;

/**
 * The texts and pictures of every page of the website, one tab per page
 * (config/content.php groups without a settings "tab").
 *
 * @property-read Schema $form
 */
class PageContent extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = AdminNavigationGroup::Content;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'page-texts';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermission('page_content.view') ?? false;
    }

    public static function canEdit(): bool
    {
        return auth()->user()?->hasPermission('page_content.update') ?? false;
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.page_content.title');
    }

    public function getTitle(): string
    {
        return __('admin.page_content.title');
    }

    /**
     * Page groups from config/content.php, keyed by group name.
     *
     * @return array<string, array{label: string, page?: string, fields: array<string, array<string, mixed>>}>
     */
    public static function groups(): array
    {
        return collect(config('content'))->reject(fn (array $group): bool => isset($group['tab']))->all();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected static function allFields(): array
    {
        return collect(static::groups())->flatMap(fn (array $group): array => $group['fields'])->all();
    }

    public function mount(): void
    {
        $this->form->fill(ContentFields::state(static::allFields()));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->disabled(fn (): bool => ! static::canEdit())
            ->components([
                Tabs::make()
                    ->persistTabInQueryString('page')
                    ->tabs(collect(static::groups())
                        ->map(fn (array $group, string $name): Tab => Tab::make(str($group['label'])->replace([' page', ' Page'], '')->toString())
                            ->id($name)
                            ->schema(ContentFields::components($group['fields'], isset($group['page'])
                                ? new HtmlString('<a href="'.e(page_url($group['page'], config('site.default_locale'))).'" target="_blank" rel="noopener" style="text-decoration:underline">'.e(__('admin.page_content.view_page')).' ↗</a>')
                                : null)))
                        ->values()
                        ->all()),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label(__('admin.actions.save'))
                                ->submit('save')
                                ->keyBindings(['mod+s'])
                                ->visible(fn (): bool => static::canEdit()),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        abort_unless(static::canEdit(), 403);

        ContentFields::save(static::allFields(), $this->form->getState());

        Notification::make()
            ->title(__('admin.page_content.saved'))
            ->success()
            ->send();
    }
}
