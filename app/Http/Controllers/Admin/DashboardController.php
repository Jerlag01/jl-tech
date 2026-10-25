<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ContactMessage;
use App\Models\JobOffer;
use App\Models\Project;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'Services' => [Service::query()->count(), route('admin.services.index')],
                'Projects' => [Project::query()->count(), route('admin.projects.index')],
                'Clients' => [Client::query()->count(), route('admin.clients.index')],
                'Job offers' => [JobOffer::query()->where('active', true)->count(), route('admin.jobs.index')],
            ],
            'unreadCount' => ContactMessage::query()->whereNull('read_at')->count(),
            'latestMessages' => ContactMessage::query()->latest()->take(6)->get(),
        ]);
    }
}
