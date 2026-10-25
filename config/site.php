<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | The languages the public website is available in. The first visit to "/"
    | redirects to the default locale.
    |
    */

    'locales' => [
        'nl' => 'Nederlands',
        'en' => 'English',
    ],

    'default_locale' => env('SITE_DEFAULT_LOCALE', 'nl'),

    /*
    |--------------------------------------------------------------------------
    | Page slugs
    |--------------------------------------------------------------------------
    |
    | Translated URL segments per page. Route names are "{locale}.{page}",
    | e.g. "nl.services" resolves to /nl/expertises.
    |
    */

    'pages' => [
        'home' => ['nl' => '', 'en' => ''],
        'services' => ['nl' => 'expertises', 'en' => 'services'],
        'projects' => ['nl' => 'realisaties', 'en' => 'projects'],
        'history' => ['nl' => 'geschiedenis', 'en' => 'history'],
        'team' => ['nl' => 'team-en-middelen', 'en' => 'team-and-resources'],
        'esg' => ['nl' => 'esg', 'en' => 'esg'],
        'jobs' => ['nl' => 'vacatures', 'en' => 'careers'],
        'who_is_who' => ['nl' => 'wie-is-wie', 'en' => 'who-is-who'],
        'news' => ['nl' => 'nieuws', 'en' => 'news'],
        'contact' => ['nl' => 'contact', 'en' => 'contact'],
        'privacy' => ['nl' => 'privacybeleid', 'en' => 'privacy-policy'],
    ],

    /*
    | Pages that can be switched off in the admin (Website settings → General).
    | "Who is who" and "News" are also hidden while they have nothing to show.
    */

    'optional_pages' => ['history', 'team', 'esg', 'jobs', 'who_is_who', 'news'],

    /*
    |--------------------------------------------------------------------------
    | Item groups
    |--------------------------------------------------------------------------
    |
    | Small repeatable blocks managed under "Page blocks" in the admin. Each
    | group lists which fields the admin form shows, with the label for each.
    |
    */

    'item_groups' => [
        'timeline' => [
            'label' => 'History – Timeline',
            'page' => 'history',
            'fields' => ['value' => 'Year', 'title' => 'Title', 'body' => 'Text'],
        ],
        'milestones' => [
            'label' => 'History – Milestones',
            'page' => 'history',
            'fields' => ['value' => 'Number / year', 'title' => 'Title', 'body' => 'Text'],
        ],
        'leaders' => [
            'label' => 'History – Leadership',
            'page' => 'history',
            'fields' => ['title' => 'Name', 'subtitle' => 'Role', 'body' => 'Text', 'icon' => 'Icon', 'image' => 'Photo'],
        ],
        'qualifications' => [
            'label' => 'Team – Qualifications',
            'page' => 'team',
            'fields' => ['title' => 'Title', 'body' => 'Text', 'icon' => 'Icon'],
        ],
        'fleet' => [
            'label' => 'Team – Fleet & equipment',
            'page' => 'team',
            'fields' => ['value' => 'Quantity (optional)', 'title' => 'Type', 'body' => 'Description', 'icon' => 'Icon'],
        ],
        'esg_pillars' => [
            'label' => 'ESG – Pillars',
            'page' => 'esg',
            'fields' => ['title' => 'Title', 'body' => 'Text', 'list' => 'Bullet points (one per line)', 'icon' => 'Icon'],
        ],
        'esg_actions' => [
            'label' => 'ESG – Concrete actions',
            'page' => 'esg',
            'fields' => ['title' => 'Title', 'body' => 'Text', 'icon' => 'Icon'],
        ],
    ],

];
