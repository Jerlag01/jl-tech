<?php

namespace App\Support;

/**
 * Line icons (24×24, stroke based) that can be picked for services and page blocks in the admin.
 */
class Icons
{
    /**
     * @var array<string, array{label: string, svg: string}>
     */
    protected const ICONS = [
        'bolt' => ['label' => 'Lightning bolt', 'svg' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'],
        'lightbulb' => ['label' => 'Light bulb', 'svg' => '<path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/>'],
        'streetlight' => ['label' => 'Street light', 'svg' => '<path d="M6 22V6a3 3 0 0 1 3-3h7"/><path d="M14 3h6l-1 3h-4z"/><path d="M17 9v1"/><path d="M15 11.5l-.5.8"/><path d="M19 11.5l.5.8"/><path d="M3 22h6"/>'],
        'tunnel' => ['label' => 'Tunnel', 'svg' => '<path d="M2 21V12a10 10 0 0 1 20 0v9"/><path d="M7 21v-8a5 5 0 0 1 10 0v8"/><path d="M2 21h20"/>'],
        'stadium' => ['label' => 'Stadium / sports', 'svg' => '<circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20"/><path d="M12 2a14.5 14.5 0 0 1 0 20"/><path d="M2 12h20"/>'],
        'building' => ['label' => 'Building', 'svg' => '<rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/>'],
        'network' => ['label' => 'Network', 'svg' => '<rect x="16" y="16" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="9" y="2" width="6" height="6" rx="1"/><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"/><path d="M12 12V8"/>'],
        'pylon' => ['label' => 'Power pylon', 'svg' => '<path d="M8 22 12 2l4 20"/><path d="M5 7h14"/><path d="M6 12h12"/><path d="M9.5 12 14 17"/><path d="M14.5 12 10 17"/><path d="M4 7v2M20 7v2"/>'],
        'fiber' => ['label' => 'Fibre / signal', 'svg' => '<path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"/><path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"/><circle cx="12" cy="12" r="2"/><path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"/><path d="M19.1 4.9C23 8.8 23 15.1 19.1 19"/>'],
        'cable' => ['label' => 'Cable', 'svg' => '<path d="M4 9a2 2 0 0 1-2-2V5h6v2a2 2 0 0 1-2 2Z"/><path d="M3 5V3"/><path d="M7 5V3"/><path d="M19 15V6.5a3.5 3.5 0 0 0-7 0v11a3.5 3.5 0 0 1-7 0V9"/><path d="M17 21v-2"/><path d="M21 21v-2"/><path d="M22 19h-6v-2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2Z"/>'],
        'construction' => ['label' => 'Civil works', 'svg' => '<rect x="2" y="6" width="20" height="8" rx="1"/><path d="M17 14v7"/><path d="M7 14v7"/><path d="M17 3v3"/><path d="M7 3v3"/><path d="M10 14 2.3 6.3"/><path d="m14 6 7.7 7.7"/><path d="m8 6 8 8"/>'],
        'traffic' => ['label' => 'Traffic light', 'svg' => '<rect x="7" y="2" width="10" height="20" rx="3"/><circle cx="12" cy="7" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="17" r="1.5"/>'],
        'shield' => ['label' => 'Shield / safety', 'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>'],
        'hardhat' => ['label' => 'Hard hat', 'svg' => '<path d="M2 18a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v2z"/><path d="M10 10V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5"/><path d="M4 15v-3a6 6 0 0 1 6-6"/><path d="M14 6a6 6 0 0 1 6 6v3"/>'],
        'truck' => ['label' => 'Truck', 'svg' => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>'],
        'crane' => ['label' => 'Lifting / crane', 'svg' => '<path d="M6 22V4l14 3"/><path d="M6 4 2 8h4"/><path d="M18 6.6V12"/><path d="M16 12h4v3h-4z"/><path d="M3 22h6"/><path d="M6 10l3-5.4"/><path d="M6 16l3-5"/>'],
        'excavator' => ['label' => 'Earthmoving', 'svg' => '<path d="M2 18h12"/><rect x="3" y="12" width="9" height="6" rx="1"/><path d="M6 12V9h4l2 3"/><path d="M12 13l5-7 4 3-2 3"/><path d="M19 12l-1 3h-3"/><circle cx="5" cy="20" r="1.5"/><circle cx="11" cy="20" r="1.5"/>'],
        'tools' => ['label' => 'Tools', 'svg' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>'],
        'users' => ['label' => 'People', 'svg' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
        'user' => ['label' => 'Person', 'svg' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
        'award' => ['label' => 'Award', 'svg' => '<circle cx="12" cy="8" r="6"/><path d="M15.48 12.89 17 22l-5-3-5 3 1.52-9.11"/>'],
        'certificate' => ['label' => 'Certificate', 'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="m9 15 2 2 4-4"/>'],
        'graduation' => ['label' => 'Training', 'svg' => '<path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/><path d="M22 10v6"/>'],
        'ladder' => ['label' => 'Working at height', 'svg' => '<path d="M8 2v20"/><path d="M16 2v20"/><path d="M8 6h8"/><path d="M8 10h8"/><path d="M8 14h8"/><path d="M8 18h8"/>'],
        'leaf' => ['label' => 'Leaf / environment', 'svg' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>'],
        'recycle' => ['label' => 'Recycling', 'svg' => '<path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/><path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12"/><path d="m14 16-3 3 3 3"/><path d="M8.293 13.596 7.196 9.5 3.1 10.598"/><path d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 11.985 3a1.784 1.784 0 0 1 1.546.888l3.943 6.843"/><path d="m13.378 9.633 4.096 1.098 1.097-4.096"/>'],
        'scale' => ['label' => 'Governance / balance', 'svg' => '<path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/>'],
        'heart' => ['label' => 'Heart / social', 'svg' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>'],
        'route' => ['label' => 'Route / logistics', 'svg' => '<circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/><circle cx="18" cy="5" r="3"/>'],
        'star' => ['label' => 'Star', 'svg' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>'],
        'check' => ['label' => 'Check', 'svg' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
        'briefcase' => ['label' => 'Briefcase', 'svg' => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>'],
        // Interface icons (not offered in the admin picker)
        'arrow' => ['label' => '', 'svg' => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>'],
        'chevron' => ['label' => '', 'svg' => '<polyline points="6 9 12 15 18 9"/>'],
        'phone' => ['label' => '', 'svg' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>'],
        'mail' => ['label' => '', 'svg' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>'],
        'map' => ['label' => '', 'svg' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>'],
        'clock' => ['label' => '', 'svg' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
        'navigation' => ['label' => '', 'svg' => '<polygon points="3 11 22 2 13 21 11 13 3 11"/>'],
        'alert' => ['label' => '', 'svg' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/>'],
        'linkedin' => ['label' => '', 'svg' => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>'],
        'facebook' => ['label' => '', 'svg' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>'],
        'instagram' => ['label' => '', 'svg' => '<rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>'],
        'tag' => ['label' => '', 'svg' => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>'],
    ];

    /**
     * Inline SVG markup for an icon; unknown names fall back to the bolt.
     */
    public static function svg(?string $name, int $size = 24, string $class = ''): string
    {
        $paths = self::ICONS[$name]['svg'] ?? self::ICONS['bolt']['svg'];
        $classAttribute = $class !== '' ? ' class="'.e($class).'"' : '';

        return '<svg'.$classAttribute.' width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$paths.'</svg>';
    }

    /**
     * Icons offered in the admin picker, as name => label.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::ICONS)
            ->filter(fn (array $icon): bool => $icon['label'] !== '')
            ->map(fn (array $icon): string => $icon['label'])
            ->all();
    }

    /**
     * @return list<string>
     */
    public static function names(): array
    {
        return array_keys(self::options());
    }
}
