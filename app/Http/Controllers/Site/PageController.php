<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Item;
use App\Models\JobOffer;
use App\Models\Project;
use App\Models\Service;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('site.home', [
            'services' => Service::query()->visible()->where('show_on_home', true)->get(),
            'featuredProject' => Project::query()->visible()->where('featured', true)->first(),
            'projects' => Project::query()->visible()->where('show_on_home', true)->take(6)->get(),
            'marqueeClients' => Client::query()->visible()->where('in_marquee', true)->get(),
            'logoClients' => Client::query()->visible()->where('in_logo_grid', true)->whereNotNull('logo')->get(),
        ]);
    }

    public function services(): View
    {
        return view('site.services', [
            'services' => Service::query()->visible()->get(),
        ]);
    }

    public function projects(): View
    {
        $projects = Project::query()->visible()->get();

        return view('site.projects', [
            'highlighted' => $projects->where('highlight', true),
            'others' => $projects->where('highlight', false),
        ]);
    }

    public function history(): View
    {
        $this->ensureEnabled('history');

        return view('site.history', [
            'timeline' => Item::forGroup('timeline'),
            'milestones' => Item::forGroup('milestones'),
            'leaders' => Item::forGroup('leaders'),
        ]);
    }

    public function team(): View
    {
        $this->ensureEnabled('team');

        return view('site.team', [
            'qualifications' => Item::forGroup('qualifications'),
            'fleet' => Item::forGroup('fleet'),
        ]);
    }

    public function esg(): View
    {
        $this->ensureEnabled('esg');

        return view('site.esg', [
            'pillars' => Item::forGroup('esg_pillars'),
            'actions' => Item::forGroup('esg_actions'),
        ]);
    }

    public function jobs(): View
    {
        $this->ensureEnabled('jobs');

        return view('site.jobs', [
            'jobs' => JobOffer::query()->visible()->get(),
            'serviceCount' => Service::query()->where('active', true)->count(),
        ]);
    }

    public function contact(): View
    {
        return view('site.contact');
    }

    public function privacy(): View
    {
        return view('site.privacy');
    }

    protected function ensureEnabled(string $page): void
    {
        abort_unless(page_enabled($page), 404);
    }
}
