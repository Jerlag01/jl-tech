<?php

/*
|--------------------------------------------------------------------------
| Editable website content
|--------------------------------------------------------------------------
|
| Every text, image and setting the admin can edit under "Page content" and
| "Settings". Values live in the `contents` table; the defaults below are
| used until a value is saved. After adding a field here, it shows up in the
| admin straight away (run `php artisan content:sync` to store defaults).
|
| Field options:
|   label    Label shown in the admin
|   type     text | textarea | html | image | number | boolean | email | url | color | code
|   tr       true when the field has a Dutch and an English version
|   default  string, or ['nl' => ..., 'en' => ...] for translated fields
|   help     optional hint shown under the field
|
| Groups with a 'tab' are edited on that tab of "Website settings" in the
| admin (general, contact, mail, appearance); the others under "Page texts".
|
*/

return [

    'company' => [
        'label' => 'Company',
        'tab' => 'general',
        'fields' => [
            'company.name' => ['label' => 'Company name', 'type' => 'text', 'default' => 'JL-Tech'],
            'company.legal_name' => ['label' => 'Legal name (footer & privacy policy)', 'type' => 'text', 'default' => 'JL-Tech'],
            'company.tagline' => ['label' => 'Tagline', 'type' => 'text', 'tr' => true, 'default' => [
                'nl' => 'Uw partner in technische infrastructuur',
                'en' => 'Your partner in technical infrastructure',
            ]],
            'company.founded_year' => ['label' => 'Founded in (year)', 'type' => 'number', 'default' => '2015', 'help' => 'Leave empty to hide this figure on the website.'],
            'company.location' => ['label' => 'Location (short)', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'België', 'en' => 'Belgium']],
            'company.vat' => ['label' => 'VAT number', 'type' => 'text', 'default' => 'BE 0000.000.000'],
            'company.registry' => ['label' => 'Company registry (RPR)', 'type' => 'text', 'default' => 'RPR'],
            'seo.description' => ['label' => 'Search engine description', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'JL-Tech – specialist in verlichting, elektrische netwerken, telecom en technische infrastructuur.',
                'en' => 'JL-Tech – specialist in lighting, electrical networks, telecom and technical infrastructure.',
            ]],
        ],
    ],

    'contact' => [
        'label' => 'Contact details',
        'tab' => 'contact',
        'fields' => [
            'contact.email' => ['label' => 'Public email address', 'type' => 'email', 'default' => 'info@jl-tech.be'],
            'contact.phone' => ['label' => 'Phone number', 'type' => 'text', 'default' => '+32 000 00 00 00'],
            'contact.street' => ['label' => 'Street and number', 'type' => 'text', 'default' => 'Straatnaam 1'],
            'contact.city' => ['label' => 'Postcode and city', 'type' => 'text', 'default' => '0000 Gemeente'],
            'contact.country' => ['label' => 'Country', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'België', 'en' => 'Belgium']],
            'contact.hours' => ['label' => 'Opening hours', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => "Maandag - vrijdag: 7u30 - 17u00\nDringende interventies 24/7",
                'en' => "Monday - Friday: 7:30 am - 5:00 pm\nEmergency call-outs 24/7",
            ]],
            'contact.phone_hours' => ['label' => 'Phone availability (short)', 'type' => 'text', 'tr' => true, 'default' => [
                'nl' => 'Ma - vr, 7u30 - 17u', 'en' => 'Mon - Fri, 7:30 am - 5 pm',
            ]],
            'contact.area' => ['label' => 'Service area', 'type' => 'text', 'tr' => true, 'default' => [
                'nl' => 'Heel België', 'en' => 'All of Belgium',
            ]],
            'contact.map_query' => ['label' => 'Address for the map', 'type' => 'text', 'default' => '', 'help' => 'Full address as you would type it in Google Maps. Leave empty to hide the map.'],
            'contact.notify_email' => ['label' => 'Send contact form messages to', 'type' => 'email', 'default' => 'info@jl-tech.be', 'help' => 'Messages are always stored under "Messages" as well. Email delivery requires mail settings in .env.'],
        ],
    ],

    'social' => [
        'label' => 'Social media',
        'tab' => 'contact',
        'fields' => [
            'social.linkedin' => ['label' => 'LinkedIn URL', 'type' => 'url', 'default' => ''],
            'social.facebook' => ['label' => 'Facebook URL', 'type' => 'url', 'default' => ''],
            'social.instagram' => ['label' => 'Instagram URL', 'type' => 'url', 'default' => ''],
        ],
    ],

    'branding' => [
        'label' => 'Logo & images',
        'tab' => 'appearance',
        'fields' => [
            'branding.logo' => ['label' => 'Logo (navigation bar, light background)', 'type' => 'image', 'help' => 'Without a logo, the company name is shown as text.'],
            'branding.logo_footer' => ['label' => 'Logo for dark background (footer)', 'type' => 'image', 'help' => 'Optional. Falls back to the main logo.'],
            'branding.favicon' => ['label' => 'Favicon', 'type' => 'image', 'help' => 'Square PNG, at least 64×64.'],
        ],
    ],

    'appearance' => [
        'label' => 'Colours',
        'tab' => 'appearance',
        'fields' => [
            'appearance.primary' => ['label' => 'Primary colour', 'type' => 'color', 'default' => '#002B45'],
            'appearance.primary_dark' => ['label' => 'Primary colour (dark)', 'type' => 'color', 'default' => '#001823'],
            'appearance.accent' => ['label' => 'Accent colour', 'type' => 'color', 'default' => '#F7941D'],
            'appearance.accent_hover' => ['label' => 'Accent colour (hover)', 'type' => 'color', 'default' => '#E58413'],
        ],
    ],

    'custom_css' => [
        'label' => 'Custom CSS',
        'tab' => 'appearance',
        'fields' => [
            'appearance.custom_css' => ['label' => 'Custom CSS', 'type' => 'code', 'default' => '', 'help' => 'Extra CSS loaded on every page of the website.'],
        ],
    ],

    'mail' => [
        'label' => 'Outgoing email',
        'tab' => 'mail',
        'fields' => [
            'mail.mailer' => ['label' => 'Delivery method', 'type' => 'text', 'default' => ''],
            'mail.host' => ['label' => 'SMTP server', 'type' => 'text', 'default' => ''],
            'mail.port' => ['label' => 'Port', 'type' => 'number', 'default' => '587'],
            'mail.scheme' => ['label' => 'Security', 'type' => 'text', 'default' => ''],
            'mail.username' => ['label' => 'Username', 'type' => 'text', 'default' => ''],
            'mail.password' => ['label' => 'Password', 'type' => 'text', 'default' => ''],
            'mail.from_address' => ['label' => 'From address', 'type' => 'email', 'default' => ''],
            'mail.from_name' => ['label' => 'From name', 'type' => 'text', 'default' => ''],
        ],
    ],

    'stats' => [
        'label' => 'Key figures',
        'tab' => 'general',
        'fields' => [
            'stats.employees' => ['label' => 'Number of employees', 'type' => 'number', 'default' => '25', 'help' => 'Leave empty to hide this figure on the website.'],
            'stats.vehicles' => ['label' => 'Number of vehicles', 'type' => 'number', 'default' => '15', 'help' => 'Leave empty to hide this figure on the website.'],
            'stats.projects' => ['label' => 'Projects completed', 'type' => 'number', 'default' => '300', 'help' => 'Leave empty to hide this figure on the website.'],
        ],
    ],

    'pages' => [
        'label' => 'Pages',
        'tab' => 'general',
        'fields' => [
            'pages.history' => ['label' => 'Show the "History" page', 'type' => 'boolean', 'default' => '1'],
            'pages.team' => ['label' => 'Show the "Team & resources" page', 'type' => 'boolean', 'default' => '1'],
            'pages.esg' => ['label' => 'Show the "ESG" page', 'type' => 'boolean', 'default' => '1'],
            'pages.jobs' => ['label' => 'Show the "Careers" page', 'type' => 'boolean', 'default' => '1'],
            'pages.who_is_who' => ['label' => 'Show the "Who is who" page', 'type' => 'boolean', 'default' => '1', 'help' => 'Only shown when at least one person is visible under "Who is who".'],
            'pages.news' => ['label' => 'Show the "News" page', 'type' => 'boolean', 'default' => '1', 'help' => 'Only shown when at least one news post is published.'],
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Page content
    |----------------------------------------------------------------------
    */

    'home' => [
        'label' => 'Home',
        'page' => 'home',
        'fields' => [
            'home.hero_image' => ['label' => 'Hero – background image', 'type' => 'image'],
            'home.hero_badge' => ['label' => 'Hero – badge', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Sinds 2015 • België', 'en' => 'Since 2015 • Belgium']],
            'home.hero_title_1' => ['label' => 'Hero – title line 1', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Wij verlichten', 'en' => 'Powering your']],
            'home.hero_title_2' => ['label' => 'Hero – title line 2 (highlighted)', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'de infrastructuur', 'en' => 'infrastructure']],
            'home.hero_title_3' => ['label' => 'Hero – title line 3', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'van morgen', 'en' => 'of tomorrow']],
            'home.hero_text' => ['label' => 'Hero – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Specialist in openbare en private verlichting, laag- en middenspanningsnetten, telecom en burgerlijke bouwkunde. Wij ontwerpen, bouwen en onderhouden de infrastructuur die onze regio draaiende houdt.',
                'en' => 'Specialists in public and private lighting, low and medium voltage networks, telecom and civil engineering. We design, build and maintain the infrastructure that keeps our region running.',
            ]],
            'home.hero_button_1' => ['label' => 'Hero – main button', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Ontdek onze projecten', 'en' => 'Discover our projects']],
            'home.hero_button_2' => ['label' => 'Hero – second button', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze expertises', 'en' => 'Our services']],
            'home.hero_card_title' => ['label' => 'Hero – figures card title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'JL-Tech in cijfers', 'en' => 'JL-Tech in numbers']],

            'home.intro_label' => ['label' => 'About – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Wie zijn wij', 'en' => 'Who we are']],
            'home.intro_title' => ['label' => 'About – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Technisch vakmanschap ten dienste van uw projecten', 'en' => 'Technical craftsmanship at the service of your projects']],
            'home.intro_text' => ['label' => 'About – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'JL-Tech ontwerpt, installeert en onderhoudt verlichtingsinstallaties, elektrische netwerken en technische uitrustingen. Onze expertise dekt de volledige keten, van ontwerp tot onderhoud.',
                'en' => 'JL-Tech designs, installs and maintains lighting installations, electrical networks and technical equipment. Our expertise covers the whole chain, from design to maintenance.',
            ]],
            'home.intro_image' => ['label' => 'About – image', 'type' => 'image'],
            'home.intro_badge_number' => ['label' => 'About – badge number', 'type' => 'text', 'default' => '10+'],
            'home.intro_badge_label' => ['label' => 'About – badge label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Jaar ervaring', 'en' => 'Years of experience']],

            'home.fig_employees_label' => ['label' => 'Figures – employees label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Medewerkers', 'en' => 'Employees']],
            'home.fig_employees_sub' => ['label' => 'Figures – employees subtext', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Een ervaren en gedreven team', 'en' => 'An experienced, dedicated team']],
            'home.fig_vehicles_label' => ['label' => 'Figures – vehicles label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Gespecialiseerde voertuigen', 'en' => 'Specialised vehicles']],
            'home.fig_vehicles_sub' => ['label' => 'Figures – vehicles subtext', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Modern en volledig uitgerust', 'en' => 'Modern and fully equipped']],
            'home.fig_founded_label' => ['label' => 'Figures – founded label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Opgericht', 'en' => 'Founded']],
            'home.fig_founded_sub' => ['label' => 'Figures – founded subtext', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Jarenlange expertise', 'en' => 'Years of expertise']],
            'home.fig_projects_label' => ['label' => 'Figures – projects label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Gerealiseerde projecten', 'en' => 'Projects completed']],
            'home.fig_projects_sub' => ['label' => 'Figures – projects subtext', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Overal in België', 'en' => 'All over Belgium']],

            'home.services_label' => ['label' => 'Services – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze expertises', 'en' => 'Our expertise']],
            'home.services_title' => ['label' => 'Services – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze kerncompetenties', 'en' => 'Our core competences']],
            'home.services_button' => ['label' => 'Services – button', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Alle expertises', 'en' => 'All services']],

            'home.featured_badge' => ['label' => 'Featured project – badge', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Uitgelicht project', 'en' => 'Featured project'], 'help' => 'The featured project is the one marked "Featured on home page" under Projects.'],
            'home.featured_button' => ['label' => 'Featured project – button', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Bekijk het project', 'en' => 'View the project']],

            'home.projects_label' => ['label' => 'Projects – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze realisaties', 'en' => 'Our projects']],
            'home.projects_title' => ['label' => 'Projects – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Projecten waar we trots op zijn', 'en' => 'Projects we are proud of']],
            'home.projects_button' => ['label' => 'Projects – button', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Alle realisaties', 'en' => 'All projects']],

            'home.clients_label' => ['label' => 'Clients – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze partners', 'en' => 'Our partners']],
            'home.clients_title' => ['label' => 'Clients – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Zij vertrouwen op ons', 'en' => 'They trust us']],

            'home.cta_label' => ['label' => 'Call to action – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Laten we praten', 'en' => "Let's talk"]],
            'home.cta_title' => ['label' => 'Call to action – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Een infrastructuurproject?', 'en' => 'An infrastructure project?']],
            'home.cta_text' => ['label' => 'Call to action – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Neem contact op met ons team om uw noden op het vlak van verlichting, elektrische netwerken of burgerlijke bouwkunde te bespreken.',
                'en' => 'Get in touch with our team to discuss your needs in lighting, electrical networks or civil engineering.',
            ]],
            'home.cta_button_1' => ['label' => 'Call to action – main button', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Contacteer ons', 'en' => 'Contact us']],
            'home.cta_button_2' => ['label' => 'Call to action – second button', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Bekijk onze realisaties', 'en' => 'See our projects']],
        ],
    ],

    'services' => [
        'label' => 'Services page',
        'page' => 'services',
        'fields' => [
            'services.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze expertises', 'en' => 'Our expertise']],
            'services.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Expertise ten dienste van uw project', 'en' => 'Expertise at the service of your project']],
            'services.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Complementaire competenties om aan al uw noden op het vlak van infrastructuur en technische uitrusting te voldoen.',
                'en' => 'Complementary skills to meet all your needs in infrastructure and technical equipment.',
            ]],
            'services.button' => ['label' => 'Button under each service', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Bekijk de realisaties', 'en' => 'See our projects']],
        ],
    ],

    'projects' => [
        'label' => 'Projects page',
        'page' => 'projects',
        'fields' => [
            'projects.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze expertise in actie', 'en' => 'Our expertise in action']],
            'projects.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze realisaties', 'en' => 'Our projects']],
            'projects.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'Projecten die getuigen van ons vakmanschap', 'en' => 'Projects that showcase our craftsmanship']],
            'projects.portfolio_label' => ['label' => 'Portfolio – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Portfolio', 'en' => 'Portfolio']],
            'projects.portfolio_title' => ['label' => 'Portfolio – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze belangrijkste projecten', 'en' => 'Our major projects']],
            'projects.portfolio_text' => ['label' => 'Portfolio – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'Ontdek de realisaties die ons vakmanschap weerspiegelen', 'en' => 'Discover the projects that reflect our know-how']],
            'projects.divider' => ['label' => 'Divider above the other projects', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Andere expertisedomeinen', 'en' => 'Other areas of expertise'], 'help' => 'Shown between highlighted projects and the rest.'],
        ],
    ],

    'history' => [
        'label' => 'History page',
        'page' => 'history',
        'fields' => [
            'history.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Ons verhaal', 'en' => 'Our story']],
            'history.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze geschiedenis', 'en' => 'Our history']],
            'history.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'Een bedrijf met ambitie en vakmanschap', 'en' => 'A company built on ambition and craftsmanship']],
            'history.intro_title' => ['label' => 'Intro – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Een bedrijf in volle groei', 'en' => 'A company in full growth']],
            'history.intro_text' => ['label' => 'Intro – text', 'type' => 'html', 'tr' => true, 'default' => [
                'nl' => "<p>JL-Tech is gespecialiseerd in het leggen van energie- en telecomkabels, elektriciteitswerken, openbare verlichting en verkeerslichten.</p>\n<p><strong>Sinds de oprichting</strong> groeide het bedrijf uit tot een betrouwbare partner voor openbare besturen, netbeheerders en private klanten, met behoud van onze waarden: kwaliteit en nabijheid.</p>",
                'en' => "<p>JL-Tech specialises in laying energy and telecom cables, electrical works, public lighting and traffic lights.</p>\n<p><strong>Since its founding</strong> the company has grown into a reliable partner for public authorities, grid operators and private clients, while keeping our values: quality and proximity.</p>",
            ]],
            'history.stat_1_value' => ['label' => 'Intro figure 1 – value', 'type' => 'text', 'default' => '10+'],
            'history.stat_1_label' => ['label' => 'Intro figure 1 – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Jaar ervaring', 'en' => 'Years of experience']],
            'history.stat_2_value' => ['label' => 'Intro figure 2 – value', 'type' => 'text', 'default' => '6'],
            'history.stat_2_label' => ['label' => 'Intro figure 2 – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Expertisedomeinen', 'en' => 'Areas of expertise']],
            'history.stat_3_value' => ['label' => 'Intro figure 3 – value', 'type' => 'text', 'default' => '±25'],
            'history.stat_3_label' => ['label' => 'Intro figure 3 – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Medewerkers', 'en' => 'Employees']],
            'history.stat_4_value' => ['label' => 'Intro figure 4 – value', 'type' => 'text', 'default' => '300+'],
            'history.stat_4_label' => ['label' => 'Intro figure 4 – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Gerealiseerde projecten', 'en' => 'Projects completed']],
            'history.timeline_label' => ['label' => 'Timeline – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Tijdlijn', 'en' => 'Timeline']],
            'history.timeline_title' => ['label' => 'Timeline – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze evolutie', 'en' => 'Our evolution']],
            'history.milestones_label' => ['label' => 'Milestones – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Kerncijfers', 'en' => 'Key figures']],
            'history.milestones_title' => ['label' => 'Milestones – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Belangrijke mijlpalen', 'en' => 'Significant milestones']],
            'history.leaders_label' => ['label' => 'Leadership – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Directie', 'en' => 'Management']],
            'history.leaders_title' => ['label' => 'Leadership – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Leiderschap en visie', 'en' => 'Leadership and vision']],
        ],
    ],

    'team' => [
        'label' => 'Team & resources page',
        'page' => 'team',
        'fields' => [
            'team.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze middelen', 'en' => 'Our resources']],
            'team.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Team & middelen', 'en' => 'Team & resources']],
            'team.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'Gekwalificeerde vakmensen en moderne uitrusting', 'en' => 'Qualified professionals and modern equipment']],
            'team.overview_label' => ['label' => 'Overview – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Menselijk kapitaal', 'en' => 'Human capital']],
            'team.overview_title' => ['label' => 'Overview – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze kracht: onze medewerkers', 'en' => 'Our strength: our people']],
            'team.overview_text' => ['label' => 'Overview – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Een team van gekwalificeerde, ervaren en gepassioneerde vakmensen. Elke medewerker draagt bij aan onze operationele uitmuntendheid.',
                'en' => 'A team of qualified, experienced and passionate professionals. Every member contributes to our operational excellence.',
            ]],
            'team.quality_value' => ['label' => 'Overview – second figure value', 'type' => 'text', 'default' => '100%'],
            'team.quality_label' => ['label' => 'Overview – second figure label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Engagement voor kwaliteit', 'en' => 'Commitment to quality']],
            'team.photo_main' => ['label' => 'Photos – large photo', 'type' => 'image'],
            'team.photo_2' => ['label' => 'Photos – small photo 1', 'type' => 'image'],
            'team.photo_3' => ['label' => 'Photos – small photo 2', 'type' => 'image'],
            'team.photo_title' => ['label' => 'Photos – caption title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Een hecht en ervaren team', 'en' => 'A close-knit, experienced team']],
            'team.photo_text' => ['label' => 'Photos – caption text', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Medewerkers die voor u klaarstaan', 'en' => 'People ready to serve you']],
            'team.qualifications_label' => ['label' => 'Qualifications – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Competenties', 'en' => 'Skills']],
            'team.qualifications_title' => ['label' => 'Qualifications – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Kwalificaties en certificaten', 'en' => 'Qualifications and certifications']],
            'team.qualifications_text' => ['label' => 'Qualifications – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Onze medewerkers beschikken over diverse beroepskwalificaties die de kwaliteit en veiligheid van onze interventies garanderen.',
                'en' => 'Our staff hold a range of professional qualifications that guarantee the quality and safety of our work.',
            ]],
            'team.fleet_label' => ['label' => 'Fleet – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Wagenpark', 'en' => 'Vehicle fleet']],
            'team.fleet_title' => ['label' => 'Fleet – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Een modern en volledig wagenpark', 'en' => 'A modern, complete fleet']],
            'team.fleet_text' => ['label' => 'Fleet – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Wij beschikken over al het materiaal en gespecialiseerd gereedschap voor de correcte uitvoering van openbare werken.',
                'en' => 'We have all the equipment and specialised tools needed to carry out public works properly.',
            ]],
            'team.fleet_total_label' => ['label' => 'Fleet – total label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Voertuigen en machines', 'en' => 'Vehicles and machines']],
        ],
    ],

    'esg' => [
        'label' => 'ESG page',
        'page' => 'esg',
        'fields' => [
            'esg.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze engagementen', 'en' => 'Our commitments']],
            'esg.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Milieu, sociaal, bestuur', 'en' => 'Environmental, social, governance']],
            'esg.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'ESG', 'en' => 'ESG']],
            'esg.intro' => ['label' => 'Introduction', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'JL-Tech zet zich in voor duurzame ontwikkeling. Onze ESG-aanpak weerspiegelt onze wil om positief bij te dragen aan de samenleving, en tegelijk de continuïteit van ons bedrijf en de tevredenheid van onze stakeholders te verzekeren.',
                'en' => 'JL-Tech is committed to responsible development. Our ESG approach reflects our wish to contribute positively to society while ensuring the continuity of our company and the satisfaction of our stakeholders.',
            ]],
            'esg.pillars_label' => ['label' => 'Pillars – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze 3 pijlers', 'en' => 'Our 3 pillars']],
            'esg.pillars_title' => ['label' => 'Pillars – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Een verantwoorde aanpak', 'en' => 'A responsible approach']],
            'esg.commitment_title' => ['label' => 'Commitment – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Ons engagement', 'en' => 'Our commitment']],
            'esg.commitment_text' => ['label' => 'Commitment – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Door verouderde installaties te vervangen door efficiënte LED-technologie dragen wij rechtstreeks bij aan de energietransitie. Elk project is een kans om het energieverbruik van onze klanten te verminderen.',
                'en' => 'By replacing outdated installations with efficient LED technology, we contribute directly to the energy transition. Every project is an opportunity to reduce our clients\' energy consumption.',
            ]],
            'esg.stat_1_value' => ['label' => 'Commitment figure 1 – value', 'type' => 'text', 'default' => '25'],
            'esg.stat_1_label' => ['label' => 'Commitment figure 1 – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Lokale jobs', 'en' => 'Local jobs']],
            'esg.stat_2_value' => ['label' => 'Commitment figure 2 – value', 'type' => 'text', 'default' => '10+'],
            'esg.stat_2_label' => ['label' => 'Commitment figure 2 – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Jaar engagement', 'en' => 'Years of commitment']],
            'esg.stat_3_value' => ['label' => 'Commitment figure 3 – value', 'type' => 'text', 'default' => '100%'],
            'esg.stat_3_label' => ['label' => 'Commitment figure 3 – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'LED-projecten', 'en' => 'LED projects']],
            'esg.actions_label' => ['label' => 'Actions – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'In de praktijk', 'en' => 'In practice']],
            'esg.actions_title' => ['label' => 'Actions – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Concrete acties', 'en' => 'Concrete actions']],
        ],
    ],

    'jobs' => [
        'label' => 'Careers page',
        'page' => 'jobs',
        'fields' => [
            'jobs.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Carrière', 'en' => 'Careers']],
            'jobs.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Word lid van ons team', 'en' => 'Join our team']],
            'jobs.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'Bouw aan je toekomst in een bedrijf op mensenmaat', 'en' => 'Build your future in a company on a human scale']],
            'jobs.intro_label' => ['label' => 'Intro – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Waarom bij ons', 'en' => 'Why join us']],
            'jobs.intro_title' => ['label' => 'Intro – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Een bedrijf op mensenmaat met boeiende projecten', 'en' => 'A human-sized company with exciting projects']],
            'jobs.intro_text' => ['label' => 'Intro – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Bij JL-Tech werk je in een stimulerende omgeving waar elke medewerker bijdraagt aan belangrijke infrastructuurprojecten. Bij ons team komen betekent deel uitmaken van een solide, innovatief en menselijk bedrijf.',
                'en' => 'At JL-Tech you work in a stimulating environment where everyone contributes to major infrastructure projects. Joining our team means becoming part of a solid, innovative and genuinely human company.',
            ]],
            'jobs.list_label' => ['label' => 'Openings – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Openstaande vacatures', 'en' => 'Open positions']],
            'jobs.list_title' => ['label' => 'Openings – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Onze vacatures', 'en' => 'Our job openings']],
            'jobs.apply_button' => ['label' => 'Apply button', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Solliciteren', 'en' => 'Apply']],
            'jobs.empty_title' => ['label' => 'No openings – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Momenteel geen vacatures', 'en' => 'No openings at the moment']],
            'jobs.empty_text' => ['label' => 'No openings – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'We hebben op dit moment geen openstaande vacatures, maar we zijn altijd op zoek naar talent. Stuur ons gerust een spontane sollicitatie!',
                'en' => "We have no open positions right now, but we're always looking for talent. Feel free to send us an open application!",
            ]],
            'jobs.empty_button' => ['label' => 'No openings – button', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Stuur je cv per e-mail', 'en' => 'Email us your CV']],
        ],
    ],

    'contact_page' => [
        'label' => 'Contact page',
        'page' => 'contact',
        'fields' => [
            'contact_page.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Blijf in contact', 'en' => 'Get in touch']],
            'contact_page.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Contacteer ons', 'en' => 'Contact us']],
            'contact_page.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'Ons team staat klaar om uw vragen te beantwoorden', 'en' => 'Our team is ready to answer your questions']],
            'contact_page.form_title' => ['label' => 'Form – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Stuur ons een bericht', 'en' => 'Send us a message']],
            'contact_page.form_text' => ['label' => 'Form – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Heeft u een vraag, een project of wenst u een offerte? Vul het formulier in en wij antwoorden zo snel mogelijk.',
                'en' => 'Do you have a question or a project, or would you like a quote? Fill in the form and we will get back to you as soon as possible.',
            ]],
            'contact_page.success' => ['label' => 'Form – thank-you message', 'type' => 'text', 'tr' => true, 'default' => [
                'nl' => 'Bedankt voor uw bericht! Wij nemen zo snel mogelijk contact met u op.',
                'en' => 'Thank you for your message! We will get back to you as soon as possible.',
            ]],
            'contact_page.info_title' => ['label' => 'Contact info – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Contactgegevens', 'en' => 'Contact details']],
            'contact_page.alt_title' => ['label' => 'Intervention box – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Interventie nodig?', 'en' => 'Need a call-out?']],
            'contact_page.alt_text' => ['label' => 'Intervention box – text', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Ons team staat klaar voor elke vraag naar een interventie, offerte of informatie.',
                'en' => 'Our team is available for any request for a call-out, quote or information.',
            ]],
            'contact_page.emergency' => ['label' => 'Emergency line text', 'type' => 'text', 'tr' => true, 'default' => ['nl' => '24/7 beschikbaar', 'en' => 'Available 24/7'], 'help' => 'Leave empty to hide the emergency line.'],
        ],
    ],

    'privacy' => [
        'label' => 'Privacy policy',
        'page' => 'privacy',
        'fields' => [
            'privacy.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Gegevensbescherming', 'en' => 'Data protection']],
            'privacy.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Privacybeleid', 'en' => 'Privacy policy']],
            'privacy.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'Hoe wij uw persoonsgegevens beschermen', 'en' => 'How we protect your personal data']],
            'privacy.body' => ['label' => 'Policy text', 'type' => 'html', 'tr' => true, 'help' => 'Basic HTML: <h2>, <p>, <ul><li>, <strong>, <a href="">.', 'default' => [
                'nl' => "<p>JL-Tech hecht veel belang aan de bescherming van uw persoonsgegevens. Dit beleid beschrijft hoe wij uw gegevens verzamelen, gebruiken en beschermen, in overeenstemming met de Algemene Verordening Gegevensbescherming (AVG/GDPR).</p>\n<h2>1. Verwerkingsverantwoordelijke</h2>\n<p>De verwerkingsverantwoordelijke is JL-Tech. U vindt onze contactgegevens onderaan deze pagina.</p>\n<h2>2. Welke gegevens verzamelen wij?</h2>\n<ul>\n<li><strong>Contactformulier:</strong> naam, e-mailadres, telefoonnummer (optioneel), onderwerp en bericht</li>\n<li><strong>Sollicitaties:</strong> cv, motivatiebrief en contactgegevens</li>\n</ul>\n<h2>3. Waarvoor gebruiken wij ze?</h2>\n<ul>\n<li>Uw vragen en aanvragen beantwoorden</li>\n<li>Uw sollicitatie behandelen</li>\n<li>Onze wettelijke verplichtingen naleven</li>\n</ul>\n<h2>4. Bewaartermijn</h2>\n<p>Contactaanvragen en sollicitaties bewaren wij maximaal 2 jaar na het laatste contact.</p>\n<h2>5. Ontvangers</h2>\n<p>Uw gegevens worden nooit verkocht. Ze kunnen gedeeld worden met technische dienstverleners (hosting, e-mail) die als verwerker optreden.</p>\n<h2>6. Uw rechten</h2>\n<p>U heeft recht op inzage, verbetering, wissing, beperking, overdraagbaarheid en bezwaar. Neem hiervoor contact met ons op; wij antwoorden binnen een maand.</p>\n<h2>7. Cookies</h2>\n<p>Deze website gebruikt enkel cookies die strikt noodzakelijk zijn voor de werking van de site.</p>\n<h2>8. Klacht</h2>\n<p>U kunt een klacht indienen bij de Gegevensbeschermingsautoriteit, Drukpersstraat 35, 1000 Brussel – <a href=\"https://www.gegevensbeschermingsautoriteit.be\" target=\"_blank\" rel=\"noopener\">www.gegevensbeschermingsautoriteit.be</a>.</p>",
                'en' => "<p>JL-Tech attaches great importance to protecting your personal data. This policy describes how we collect, use and protect your data in accordance with the General Data Protection Regulation (GDPR).</p>\n<h2>1. Data controller</h2>\n<p>The data controller is JL-Tech. You will find our contact details at the bottom of this page.</p>\n<h2>2. What data do we collect?</h2>\n<ul>\n<li><strong>Contact form:</strong> name, email address, phone number (optional), subject and message</li>\n<li><strong>Applications:</strong> CV, cover letter and contact details</li>\n</ul>\n<h2>3. What do we use it for?</h2>\n<ul>\n<li>Answering your questions and requests</li>\n<li>Processing your job application</li>\n<li>Complying with our legal obligations</li>\n</ul>\n<h2>4. Retention period</h2>\n<p>We keep contact requests and applications for up to 2 years after the last contact.</p>\n<h2>5. Recipients</h2>\n<p>Your data is never sold. It may be shared with technical service providers (hosting, email) acting as processors.</p>\n<h2>6. Your rights</h2>\n<p>You have the right to access, rectify, erase, restrict, port and object. Contact us to exercise them; we reply within one month.</p>\n<h2>7. Cookies</h2>\n<p>This website only uses cookies that are strictly necessary for it to work.</p>\n<h2>8. Complaints</h2>\n<p>You can lodge a complaint with the Belgian Data Protection Authority, Rue de la Presse 35, 1000 Brussels – <a href=\"https://www.dataprotectionauthority.be\" target=\"_blank\" rel=\"noopener\">www.dataprotectionauthority.be</a>.</p>",
            ]],
            'privacy.updated' => ['label' => 'Last updated', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Laatst bijgewerkt: oktober 2026', 'en' => 'Last updated: October 2026']],
        ],
    ],

    'who_is_who' => [
        'label' => 'Who is who page',
        'page' => 'who_is_who',
        'fields' => [
            'who_is_who.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Ons team', 'en' => 'Our team']],
            'who_is_who.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Wie is wie', 'en' => 'Who is who']],
            'who_is_who.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'Maak kennis met de mensen achter JL-Tech', 'en' => 'Meet the people behind JL-Tech']],
        ],
    ],

    'news' => [
        'label' => 'News page',
        'page' => 'news',
        'fields' => [
            'news.label' => ['label' => 'Header – label', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Actueel', 'en' => 'Latest']],
            'news.title' => ['label' => 'Header – title', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Nieuws', 'en' => 'News']],
            'news.text' => ['label' => 'Header – text', 'type' => 'textarea', 'tr' => true, 'default' => ['nl' => 'Het laatste nieuws over onze projecten en ons bedrijf', 'en' => 'The latest news about our projects and our company']],
            'news.read_more' => ['label' => 'Read more link', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Lees meer', 'en' => 'Read more']],
            'news.back' => ['label' => 'Back link on a post', 'type' => 'text', 'tr' => true, 'default' => ['nl' => 'Terug naar het nieuws', 'en' => 'Back to the news']],
        ],
    ],

    'footer' => [
        'label' => 'Footer',
        'fields' => [
            'footer.description' => ['label' => 'Description under the logo', 'type' => 'textarea', 'tr' => true, 'default' => [
                'nl' => 'Specialist in verlichting, elektrische netwerken en technische infrastructuur.',
                'en' => 'Specialists in lighting, electrical networks and technical infrastructure.',
            ]],
        ],
    ],

];
