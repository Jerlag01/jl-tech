@extends('layouts.site', ['title' => __('Contact')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'contact_page'])

    @php
        $email = content('contact.email');
        $phone = content('contact.phone');
        $mapQuery = content('contact.map_query');
    @endphp

    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-box reveal-left" id="contact-form">
                    <h2>{{ content('contact_page.form_title') }}</h2>
                    <p>{{ content('contact_page.form_text') }}</p>

                    @if (session('contact_sent'))
                        <div class="form-alert form-alert--success" role="status">{{ content('contact_page.success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="form-alert form-alert--error" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route(app()->getLocale().'.contact.send') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ __('Full name') }} *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input @error('name') is-invalid @enderror" placeholder="{{ __('Your first and last name') }}" required autocomplete="name">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">{{ __('Email') }} *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input @error('email') is-invalid @enderror" placeholder="{{ __('your@email.com') }}" required autocomplete="email">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="phone">{{ __('Phone') }}</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="+32 ..." autocomplete="tel">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="subject">{{ __('Subject') }} *</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="form-input @error('subject') is-invalid @enderror" placeholder="{{ __('What is your request about?') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="message">{{ __('Message') }} *</label>
                            <textarea id="message" name="message" class="form-textarea @error('message') is-invalid @enderror" placeholder="{{ __('Describe your request, project or question...') }}" required>{{ old('message') }}</textarea>
                        </div>
                        <div class="form-hp" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="form-checkbox-group">
                            <input type="checkbox" id="privacy" name="privacy" value="1" class="form-checkbox" @checked(old('privacy')) required>
                            <label for="privacy" class="form-checkbox-label">
                                {!! __('I agree that my data is processed in accordance with the :link.', ['link' => '<a href="'.e(page_url('privacy')).'">'.e(__('privacy policy')).'</a>']) !!} *
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-large form-submit">{{ __('Send message') }} <x-icon name="arrow" size="16" /></button>
                    </form>
                </div>

                <div class="reveal-right">
                    <div class="contact-info-box">
                        <h3>{{ content('contact_page.info_title') }}</h3>
                        <div class="info-item">
                            <div class="info-icon"><x-icon name="map" size="20" /></div>
                            <div>
                                <div class="info-label">{{ __('Address') }}</div>
                                <div class="info-value">{{ content('contact.street') }}<br>{{ content('contact.city') }}<br>{{ content('contact.country') }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><x-icon name="clock" size="20" /></div>
                            <div>
                                <div class="info-label">{{ __('Opening hours') }}</div>
                                <div class="info-value">{!! nl2br(e(content('contact.hours'))) !!}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><x-icon name="navigation" size="20" /></div>
                            <div>
                                <div class="info-label">{{ __('Service area') }}</div>
                                <div class="info-value">{{ content('contact.area') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="alternative-contact">
                        <h4>{{ content('contact_page.alt_title') }}</h4>
                        <p>{{ content('contact_page.alt_text') }}</p>
                        <div class="contact-methods">
                            @if ($phone)
                                <a href="tel:{{ preg_replace('/[^+\d]/', '', $phone) }}" class="contact-method">
                                    <x-icon name="phone" size="20" />
                                    <span><span class="contact-method-text">{{ $phone }}</span><span class="contact-method-sub">{{ content('contact.phone_hours') }}</span></span>
                                </a>
                            @endif
                            @if ($email)
                                <a href="mailto:{{ $email }}" class="contact-method">
                                    <x-icon name="mail" size="20" />
                                    <span><span class="contact-method-text">{{ __('Email') }}</span><span class="contact-method-sub">{{ $email }}</span></span>
                                </a>
                            @endif
                            @if (content('contact_page.emergency'))
                                <div class="contact-method">
                                    <x-icon name="alert" size="20" />
                                    <span><span class="contact-method-text">{{ __('Emergencies') }}</span><span class="contact-method-sub">{{ content('contact_page.emergency') }}</span></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($mapQuery)
        <section class="map-section reveal">
            <div class="map-container">
                <iframe src="https://www.google.com/maps?q={{ urlencode($mapQuery) }}&output=embed" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="{{ __('Map') }}"></iframe>
                <div class="map-overlay">
                    <div class="map-address">
                        <h3>{{ content('company.legal_name') }}</h3>
                        <p>{{ $mapQuery }}</p>
                    </div>
                    <a href="https://www.google.com/maps/dir//{{ urlencode($mapQuery) }}" target="_blank" rel="noopener" class="btn btn-primary"><x-icon name="navigation" size="16" /> {{ __('Directions') }}</a>
                </div>
            </div>
        </section>
    @endif
@endsection
