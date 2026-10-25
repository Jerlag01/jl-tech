<?php

namespace App\Filament\Pages;

use App\Enums\AdminNavigationGroup;
use App\Filament\Support\ContentFields;
use App\Filament\Support\TranslatableTabs;
use App\Models\Content;
use App\Models\Department;
use App\Models\SlaPlan;
use App\Support\Settings as SiteSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Throwable;
use UnitEnum;

/**
 * Website settings: company, contact details, outgoing email and appearance. The values are
 * content fields (config/content.php groups with a "tab"), so the public website reads them as before.
 *
 * @property-read Schema $form
 */
class Settings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = AdminNavigationGroup::System;

    protected static ?int $navigationSort = 1;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->role?->allowsAny('settings') ?? false;
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.settings.title');
    }

    public function getTitle(): string
    {
        return __('admin.settings.title');
    }

    public static function canEdit(string $tab): bool
    {
        return auth()->user()?->hasPermission("settings.{$tab}") ?? false;
    }

    /**
     * Content field definitions edited on a tab.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function fieldsOnTab(string $tab): array
    {
        return collect(config('content'))
            ->filter(fn (array $group): bool => ($group['tab'] ?? null) === $tab)
            ->flatMap(fn (array $group): array => $group['fields'])
            ->all();
    }

    public function mount(): void
    {
        $state = ContentFields::state(collect(['general', 'contact', 'mail', 'appearance', 'helpdesk'])
            ->flatMap(fn (string $tab): array => static::fieldsOnTab($tab))
            ->all());

        data_set($state, 'mail.password', null);

        $this->form->fill($state);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make(__('admin.settings.tabs.general'))
                            ->visible(fn (): bool => static::canEdit('general'))
                            ->icon(Heroicon::OutlinedHome)
                            ->schema($this->generalFields()),
                        Tab::make(__('admin.settings.tabs.contact'))
                            ->visible(fn (): bool => static::canEdit('contact'))
                            ->icon(Heroicon::OutlinedMapPin)
                            ->schema($this->contactFields()),
                        Tab::make(__('admin.settings.tabs.mail'))
                            ->visible(fn (): bool => static::canEdit('mail'))
                            ->icon(Heroicon::OutlinedEnvelope)
                            ->schema($this->mailFields()),
                        Tab::make(__('admin.settings.tabs.appearance'))
                            ->visible(fn (): bool => static::canEdit('appearance'))
                            ->icon(Heroicon::OutlinedSwatch)
                            ->schema($this->appearanceFields()),
                        Tab::make(__('admin.settings.tabs.helpdesk'))
                            ->visible(fn (): bool => static::canEdit('helpdesk'))
                            ->icon(Heroicon::OutlinedLifebuoy)
                            ->schema($this->helpdeskFields()),
                    ]),
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
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (static::canEdit('mail')) {
            data_set($data, 'mail.password', filled(data_get($data, 'mail.password'))
                ? Crypt::encryptString(data_get($data, 'mail.password'))
                : Content::text('mail.password'));
        }

        // Only tabs the user may edit are saved, whatever the request contains.
        foreach (['general', 'contact', 'mail', 'appearance', 'helpdesk'] as $tab) {
            if (static::canEdit($tab)) {
                ContentFields::save(static::fieldsOnTab($tab), $data);
            }
        }

        data_set($this->data, 'mail.password', null);

        Notification::make()
            ->title(__('admin.settings.saved'))
            ->success()
            ->send();
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendTestMail')
                ->label(__('admin.settings.test_mail'))
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->color('gray')
                ->visible(fn (): bool => static::canEdit('mail'))
                ->requiresConfirmation()
                ->modalDescription(fn (): string => __('admin.settings.test_mail_description', ['email' => auth()->user()->email]))
                ->action(function (): void {
                    SiteSettings::applyMailConfig();
                    Mail::purge();

                    try {
                        Mail::raw(__('admin.settings.test_mail_body', ['site' => content('company.name')]), function ($message): void {
                            $message->to(auth()->user()->email)->subject(content('company.name').' - test');
                        });

                        Notification::make()->title(__('admin.settings.test_mail_sent'))->success()->send();
                    } catch (Throwable $exception) {
                        Notification::make()
                            ->title(__('admin.settings.test_mail_failed'))
                            ->body($exception->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }

    /**
     * @return array<Component|Field>
     */
    protected function generalFields(): array
    {
        return [
            Section::make(__('admin.settings.sections.identity'))
                ->columns(2)
                ->schema([
                    TextInput::make('company.name')
                        ->label(__('admin.settings.fields.site_name'))
                        ->required()
                        ->maxLength(100),
                    TextInput::make('company.legal_name')
                        ->label(__('admin.settings.fields.legal_name'))
                        ->maxLength(150),
                    TextInput::make('company.vat')
                        ->label(__('admin.settings.fields.vat'))
                        ->placeholder('BE 0123.456.789')
                        ->maxLength(30),
                    TextInput::make('company.registry')
                        ->label(__('admin.settings.fields.registry'))
                        ->maxLength(60),
                ]),
            TranslatableTabs::make(fn (string $locale, bool $isDefault): array => [
                Grid::make(2)->schema([
                    TextInput::make("company.tagline.{$locale}")
                        ->label(__('admin.settings.fields.tagline'))
                        ->required($isDefault)
                        ->maxLength(150),
                    TextInput::make("company.location.{$locale}")
                        ->label(__('admin.settings.fields.location'))
                        ->maxLength(100),
                ]),
                Textarea::make("seo.description.{$locale}")
                    ->label(__('admin.settings.fields.seo_description'))
                    ->rows(2)
                    ->maxLength(300),
            ]),
            Section::make(__('admin.settings.sections.figures'))
                ->description(__('admin.settings.help.figures'))
                ->columns(4)
                ->schema([
                    TextInput::make('company.founded_year')
                        ->label(__('admin.settings.fields.founded'))
                        ->numeric()
                        ->minValue(1800)
                        ->maxValue((int) date('Y')),
                    TextInput::make('stats.employees')
                        ->label(__('admin.settings.fields.employees'))
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('stats.vehicles')
                        ->label(__('admin.settings.fields.vehicles'))
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('stats.projects')
                        ->label(__('admin.settings.fields.projects'))
                        ->numeric()
                        ->minValue(0),
                ]),
            Section::make(__('admin.settings.sections.pages'))
                ->description(__('admin.settings.help.pages'))
                ->columns(2)
                ->schema(collect(['history', 'team', 'esg', 'jobs', 'who_is_who', 'news'])
                    ->map(fn (string $page): Toggle => Toggle::make("pages.{$page}")
                        ->label(__("admin.settings.fields.show_{$page}")))
                    ->all()),
        ];
    }

    /**
     * @return array<Component|Field>
     */
    protected function contactFields(): array
    {
        return [
            Section::make(__('admin.settings.sections.address'))
                ->columns(2)
                ->schema([
                    TextInput::make('contact.street')
                        ->label(__('admin.settings.fields.street'))
                        ->maxLength(150),
                    TextInput::make('contact.city')
                        ->label(__('admin.settings.fields.city'))
                        ->maxLength(150),
                    TextInput::make('contact.phone')
                        ->label(__('admin.settings.fields.phone'))
                        ->tel()
                        ->maxLength(30),
                    TextInput::make('contact.email')
                        ->label(__('admin.settings.fields.email'))
                        ->email()
                        ->maxLength(150),
                    TextInput::make('contact.notify_email')
                        ->label(__('admin.settings.fields.notify_email'))
                        ->helperText(__('admin.settings.help.notify_email'))
                        ->email()
                        ->maxLength(150),
                    TextInput::make('contact.map_query')
                        ->label(__('admin.settings.fields.map_query'))
                        ->helperText(__('admin.settings.help.map_query'))
                        ->maxLength(255),
                ]),
            TranslatableTabs::make(fn (string $locale, bool $isDefault): array => [
                Grid::make(2)->schema([
                    TextInput::make("contact.country.{$locale}")
                        ->label(__('admin.settings.fields.country'))
                        ->maxLength(100),
                    TextInput::make("contact.area.{$locale}")
                        ->label(__('admin.settings.fields.service_area'))
                        ->maxLength(150),
                    Textarea::make("contact.hours.{$locale}")
                        ->label(__('admin.settings.fields.opening_hours'))
                        ->rows(2)
                        ->maxLength(300),
                    TextInput::make("contact.phone_hours.{$locale}")
                        ->label(__('admin.settings.fields.phone_hours'))
                        ->maxLength(100),
                ]),
            ], __('admin.settings.sections.localized_contact')),
            Section::make(__('admin.settings.sections.social'))
                ->description(__('admin.settings.help.social'))
                ->columns(3)
                ->schema(collect(['linkedin' => 'LinkedIn', 'facebook' => 'Facebook', 'instagram' => 'Instagram'])
                    ->map(fn (string $label, string $network): TextInput => TextInput::make("social.{$network}")
                        ->label($label)
                        ->url()
                        ->rule('url:http,https')
                        ->maxLength(255)
                        ->placeholder("https://{$network}.com/…")
                        ->prefixIcon(Heroicon::OutlinedLink))
                    ->values()
                    ->all()),
        ];
    }

    /**
     * @return array<Component|Field>
     */
    protected function mailFields(): array
    {
        $usesSmtp = fn (Get $get): bool => $get('mail.mailer') === 'smtp';

        return [
            Section::make(__('admin.settings.sections.mail'))
                ->description(__('admin.settings.help.mail'))
                ->columns(2)
                ->schema([
                    Select::make('mail.mailer')
                        ->label(__('admin.settings.fields.mailer'))
                        ->options([
                            'smtp' => 'SMTP',
                            'sendmail' => 'Sendmail',
                            'log' => __('admin.settings.mailers.log'),
                        ])
                        ->placeholder(__('admin.settings.mailers.env'))
                        ->live(),
                    TextInput::make('mail.host')
                        ->label(__('admin.settings.fields.host'))
                        ->placeholder('smtp.example.com')
                        ->required($usesSmtp)
                        ->visible($usesSmtp),
                    TextInput::make('mail.port')
                        ->label(__('admin.settings.fields.port'))
                        ->numeric()
                        ->default(587)
                        ->required($usesSmtp)
                        ->visible($usesSmtp),
                    Select::make('mail.scheme')
                        ->label(__('admin.settings.fields.scheme'))
                        ->options([
                            'smtp' => 'STARTTLS (587)',
                            'smtps' => 'SSL/TLS (465)',
                        ])
                        ->placeholder(__('admin.settings.fields.scheme_auto'))
                        ->visible($usesSmtp),
                    TextInput::make('mail.username')
                        ->label(__('admin.settings.fields.username'))
                        ->autocomplete('off')
                        ->visible($usesSmtp),
                    TextInput::make('mail.password')
                        ->label(__('admin.fields.password'))
                        ->password()
                        ->revealable()
                        ->autocomplete('new-password')
                        ->helperText(__('admin.settings.help.mail_password'))
                        ->visible($usesSmtp),
                    TextInput::make('mail.from_address')
                        ->label(__('admin.settings.fields.from_address'))
                        ->email(),
                    TextInput::make('mail.from_name')
                        ->label(__('admin.settings.fields.from_name')),
                ]),
        ];
    }

    /**
     * @return array<Component|Field>
     */
    protected function appearanceFields(): array
    {
        return [
            Section::make(__('admin.settings.sections.colors'))
                ->description(__('admin.settings.help.colors'))
                ->columns(2)
                ->schema(collect(['primary', 'primary_dark', 'accent', 'accent_hover'])
                    ->map(fn (string $color): ColorPicker => ColorPicker::make("appearance.{$color}")
                        ->label(__("admin.settings.fields.{$color}"))
                        ->regex('/^#[0-9a-fA-F]{6}$/')
                        ->required())
                    ->all()),
            Section::make(__('admin.settings.sections.logo'))
                ->columns(2)
                ->schema([
                    $this->imageUpload('branding.logo', 'branding', 2048)
                        ->label(__('admin.settings.fields.logo'))
                        ->helperText(__('admin.settings.help.logo')),
                    $this->imageUpload('branding.logo_footer', 'branding', 2048)
                        ->label(__('admin.settings.fields.logo_footer'))
                        ->helperText(__('admin.settings.help.logo_footer')),
                ]),
            Section::make(__('admin.settings.sections.favicon'))
                ->description(__('admin.settings.help.favicon'))
                ->schema([
                    $this->imageUpload('branding.favicon', 'branding', 512, ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/webp'])
                        ->hiddenLabel()
                        ->imagePreviewHeight('64'),
                ]),
            Section::make(__('admin.settings.sections.custom_css'))
                ->description(__('admin.settings.help.custom_css'))
                ->schema([
                    Textarea::make('appearance.custom_css')
                        ->hiddenLabel()
                        ->rows(12)
                        ->extraInputAttributes(['class' => 'font-mono', 'spellcheck' => 'false'])
                        ->placeholder(".hero-title {\n    letter-spacing: -0.02em;\n}"),
                ]),
        ];
    }

    /**
     * @return array<Component|Field>
     */
    protected function helpdeskFields(): array
    {
        return [
            Section::make(__('admin.settings.sections.routing'))
                ->description(__('admin.settings.help.routing'))
                ->columns(2)
                ->schema([
                    Select::make('helpdesk.default_department_id')
                        ->label(__('admin.settings.fields.default_department'))
                        ->options(fn (): array => Department::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->placeholder(__('admin.settings.fields.first_department')),
                    Select::make('helpdesk.default_sla_plan_id')
                        ->label(__('admin.settings.fields.default_sla_plan'))
                        ->options(fn (): array => SlaPlan::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id')->all())
                        ->placeholder(__('admin.settings.fields.no_sla_plan')),
                ]),
            Section::make(__('admin.settings.sections.portal'))
                ->columns(2)
                ->schema([
                    Toggle::make('helpdesk.allow_registration')
                        ->label(__('admin.settings.fields.allow_registration'))
                        ->helperText(__('admin.settings.help.allow_registration')),
                    Toggle::make('helpdesk.clients_can_reopen')
                        ->label(__('admin.settings.fields.clients_can_reopen')),
                    Toggle::make('helpdesk.auto_assign_on_reply')
                        ->label(__('admin.settings.fields.auto_assign_on_reply'))
                        ->helperText(__('admin.settings.help.auto_assign_on_reply')),
                    Toggle::make('helpdesk.show_knowledge_base')
                        ->label(__('admin.settings.fields.show_knowledge_base')),
                ]),
        ];
    }

    /**
     * @param  list<string>  $types
     */
    protected function imageUpload(string $name, string $directory, int $maxKilobytes, array $types = ['image/png', 'image/webp', 'image/jpeg']): FileUpload
    {
        // No SVG uploads: an SVG on the public disk could carry script.
        return FileUpload::make($name)
            ->image()
            ->acceptedFileTypes($types)
            ->maxSize($maxKilobytes)
            ->disk('public')
            ->directory("uploads/{$directory}")
            ->visibility('public')
            ->imagePreviewHeight('80');
    }
}
