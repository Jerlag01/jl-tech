@extends('layouts.site', ['title' => __('Privacy policy')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'privacy'])

    <section class="privacy-section">
        <div class="container">
            <div class="privacy-content">
                <div class="rich-text reveal">{!! content('privacy.body') !!}</div>

                <div class="privacy-contact-box reveal">
                    <h3>{{ content('company.legal_name') }}</h3>
                    <p>
                        {{ content('contact.street') }}<br>
                        {{ content('contact.city') }}<br>
                        {{ content('contact.country') }}<br>
                        {{ __('VAT') }}: {{ content('company.vat') }}
                        @if (content('contact.email'))
                            <br>{{ __('Email') }}: <a href="mailto:{{ content('contact.email') }}">{{ content('contact.email') }}</a>
                        @endif
                    </p>
                </div>

                @if (content('privacy.updated'))
                    <p class="privacy-update">{{ content('privacy.updated') }}</p>
                @endif
            </div>
        </div>
    </section>
@endsection
