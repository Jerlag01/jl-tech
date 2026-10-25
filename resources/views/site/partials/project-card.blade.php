<article @class(['project-card', 'reveal-scale', 'featured' => $wide ?? false])>
    <div class="project-card-image">
        @if ($project->tr('badge'))
            <span class="project-badge">{{ $project->tr('badge') }}</span>
        @endif
        <div class="project-category-icon"><x-site-icon :name="$project->service?->icon" size="20" /></div>
        @include('site.partials.image', ['src' => media_url($project->image), 'alt' => $project->tr('title'), 'icon' => $project->service?->icon])
    </div>
    <div class="project-card-content">
        @if ($project->tr('category'))
            <span class="project-category">{{ $project->tr('category') }}</span>
        @endif
        <h3>{{ $project->tr('title') }}</h3>
        <p>{{ $project->tr('description') }}</p>
        @if ($stats = $project->trStats())
            <div class="project-stats">
                @foreach ($stats as $stat)
                    <div class="project-stat"><x-site-icon name="check" size="16" /> <span><strong>{{ $stat['value'] }}</strong> {{ $stat['label'] }}</span></div>
                @endforeach
            </div>
        @endif
    </div>
</article>
