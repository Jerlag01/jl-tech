@extends('layouts.site', ['title' => __('page.who_is_who')])

@section('content')
    @include('site.partials.page-header', ['prefix' => 'who_is_who'])

    <section class="people-section">
        <div class="container">
            <div class="people-grid">
                @foreach ($employees as $employee)
                    <article class="person-card reveal-scale reveal-delay-{{ ($loop->index % 4) + 1 }}">
                        <div class="person-photo">
                            @if ($employee->photo)
                                <img src="{{ media_url($employee->photo) }}" alt="{{ $employee->name }}" loading="lazy">
                            @else
                                <div class="person-photo-placeholder" aria-hidden="true">{{ str($employee->name)->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}</div>
                            @endif
                        </div>
                        <div class="person-body">
                            <h3>{{ $employee->name }}</h3>
                            @if ($employee->tr('job_title'))
                                <div class="person-role">{{ $employee->tr('job_title') }}</div>
                            @endif
                            <ul class="person-contact">
                                @if ($employee->phone)
                                    <li><x-site-icon name="phone" size="15" /> <a href="{{ \App\Models\Employee::telLink($employee->phone) }}">{{ $employee->phone }}</a></li>
                                @endif
                                @if ($employee->mobile)
                                    <li><x-site-icon name="phone" size="15" /> <a href="{{ \App\Models\Employee::telLink($employee->mobile) }}">{{ $employee->mobile }}</a></li>
                                @endif
                                @if ($employee->email)
                                    <li><x-site-icon name="mail" size="15" /> <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></li>
                                @endif
                            </ul>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @include('site.partials.cta')
@endsection
