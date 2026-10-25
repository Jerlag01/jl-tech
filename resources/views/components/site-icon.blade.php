@props(['name' => 'bolt', 'size' => 24])
{!! \App\Support\Icons::svg($name, (int) $size, (string) $attributes->get('class', '')) !!}
