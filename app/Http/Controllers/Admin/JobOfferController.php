<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOfferController extends AdminController
{
    protected const TRANSLATED = ['title', 'badge', 'tags', 'description'];

    public function index(): View
    {
        return view('admin.jobs.index', [
            'jobs' => JobOffer::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.jobs.form', [
            'jobOffer' => new JobOffer(['active' => true, 'sort' => JobOffer::query()->max('sort') + 1]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new JobOffer);

        return redirect()->route('admin.jobs.index')->with('status', 'Job offer created.');
    }

    public function edit(JobOffer $jobOffer): View
    {
        return view('admin.jobs.form', ['jobOffer' => $jobOffer]);
    }

    public function update(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        $this->save($request, $jobOffer);

        return redirect()->route('admin.jobs.index')->with('status', 'Job offer saved.');
    }

    public function destroy(JobOffer $jobOffer): RedirectResponse
    {
        $jobOffer->delete();

        return redirect()->route('admin.jobs.index')->with('status', 'Job offer deleted.');
    }

    protected function save(Request $request, JobOffer $jobOffer): void
    {
        $request->validate([
            ...$this->translatedRules(self::TRANSLATED, ['title']),
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);

        $jobOffer->fill([
            ...$this->translatedMany($request, self::TRANSLATED),
            'active' => $request->boolean('active'),
            'sort' => (int) $request->input('sort', 0),
        ])->save();
    }
}
