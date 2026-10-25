<section class="cta">
    <div class="container">
        <div class="cta-inner reveal">
            <span class="eyebrow eyebrow-center">{{ content('home.cta_label') }}</span>
            <h2>{{ content('home.cta_title') }}</h2>
            <p>{{ content('home.cta_text') }}</p>
            <div class="cta-actions">
                <a href="{{ page_url('contact') }}" class="btn btn-primary btn-large">{{ content('home.cta_button_1') }} <x-icon name="arrow" size="16" /></a>
                <a href="{{ page_url('projects') }}" class="btn btn-outline btn-large">{{ content('home.cta_button_2') }}</a>
            </div>
        </div>
    </div>
</section>
