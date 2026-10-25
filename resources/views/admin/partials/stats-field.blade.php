{{-- Repeatable "value + label" rows. Params: $stats (list), $help --}}
@php
    $rows = old('stats', $stats ?? []);
    $rows = array_pad(array_values($rows), max(count($rows) + 1, 3), ['value' => '', 'label' => []]);
@endphp
<div class="field">
    <span class="field-label">Key figures</span>
    <table class="stats-table">
        <thead>
            <tr>
                <th>Value</th>
                @foreach (config('site.locales') as $locale => $localeLabel)
                    <th>Label ({{ strtoupper($locale) }})</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $index => $row)
                <tr>
                    <td><input type="text" name="stats[{{ $index }}][value]" value="{{ $row['value'] ?? '' }}" class="input" placeholder="e.g. 200+"></td>
                    @foreach (config('site.locales') as $locale => $localeLabel)
                        <td><input type="text" name="stats[{{ $index }}][label][{{ $locale }}]" value="{{ $row['label'][$locale] ?? '' }}" class="input"></td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <span class="field-help">{{ $help ?? 'Rows without a value are ignored. Save to get an extra empty row.' }}</span>
</div>
