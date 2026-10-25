@php
    $footerServices = \App\Models\Service::query()->visible()->get(['id', 'slug', 'title']);
    $companyPages = collect(['history', 'team', 'who_is_who', 'esg'])->filter(fn ($p) => page_enabled($p));
    $footerLinks = \App\Models\FooterLink::forFooter();
    $email = content('contact.email');
    $socials = collect(['linkedin', 'facebook', 'instagram'])->mapWithKeys(fn ($s) => [$s => content('social.'.$s)])->filter();
@endphp
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col footer-brand">
                <a href="{{ page_url('home') }}" class="footer-logo-link">
                    @include('site.partials.logo', ['footer' => true])
                </a>
                <p class="footer-description">{{ content('footer.description') }}</p>
                <div class="footer-social">
                    @foreach ($socials as $network => $url)
                        <a href="{{ $url }}" class="social-link" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}"><x-site-icon :name="$network" size="16" /></a>
                    @endforeach
                    @if ($email)
                        <a href="mailto:{{ $email }}" class="social-link" aria-label="Email"><x-site-icon name="mail" size="16" /></a>
                    @endif
                </div>
            </div>
            <div class="footer-col">
                <h4 class="footer-heading">{{ __('Navigation') }}</h4>
                <ul class="footer-links">
                    <li><a href="{{ page_url('home') }}">{{ __('Home') }}</a></li>
                    @if ($footerServices->isNotEmpty())
                        <li><a href="{{ page_url('services') }}">{{ __('Services') }}</a></li>
                    @endif
                    <li><a href="{{ page_url('projects') }}">{{ __('Projects') }}</a></li>
                    @if (page_enabled('news'))
                        <li><a href="{{ page_url('news') }}">{{ __('News') }}</a></li>
                    @endif
                    @if (page_enabled('jobs'))
                        <li><a href="{{ page_url('jobs') }}">{{ __('Careers') }}</a></li>
                    @endif
                    <li><a href="{{ support_route('home') }}">{{ __('Support') }}</a></li>
                    <li><a href="{{ page_url('contact') }}">{{ __('Contact') }}</a></li>
                </ul>
            </div>
            @if ($footerServices->isNotEmpty())
                <div class="footer-col">
                    <h4 class="footer-heading">{{ __('Services') }}</h4>
                    <ul class="footer-links">
                        @foreach ($footerServices as $service)
                            <li><a href="{{ page_url('services', null, $service->slug) }}">{{ $service->tr('title') }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="footer-col">
                @if ($companyPages->isNotEmpty())
                    <h4 class="footer-heading">{{ __('Company') }}</h4>
                    <ul class="footer-links">
                        @foreach ($companyPages as $companyPage)
                            <li><a href="{{ page_url($companyPage) }}">{{ __('page.'.$companyPage) }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <h4 class="footer-heading">{{ __('Contact') }}</h4>
                    <ul class="footer-links">
                        <li>{{ content('contact.street') }}, {{ content('contact.city') }}</li>
                        <li><a href="tel:{{ preg_replace('/[^+\d]/', '', content('contact.phone')) }}">{{ content('contact.phone') }}</a></li>
                    </ul>
                @endif
            </div>
        </div>
        @if ($footerLinks->isNotEmpty())
            <ul class="footer-extra-links">
                @foreach ($footerLinks as $link)
                    <li><a href="{{ $link->url }}" @if ($link->open_in_new_tab) target="_blank" rel="noopener" @endif>{{ $link->tr('label') }}</a></li>
                @endforeach
            </ul>
        @endif
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ content('company.legal_name') }}. {{ __('All rights reserved.') }} | <a href="{{ page_url('privacy') }}">{{ __('Privacy policy') }}</a></p>
            <p>{{ __('VAT') }}: {{ content('company.vat') }} @if (content('company.registry')) | {{ content('company.registry') }} @endif</p>
        </div>
    </div>
</footer>
