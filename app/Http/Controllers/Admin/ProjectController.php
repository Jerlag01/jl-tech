<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends AdminController
{
    protected const TRANSLATED = ['title', 'category', 'badge', 'description'];

    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::query()->ordered()->with('service')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new Project(['active' => true, 'sort' => Project::query()->max('sort') + 1]),
            'services' => Service::query()->ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Project);

        return redirect()->route('admin.projects.index')->with('status', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'services' => Service::query()->ordered()->get(),
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->save($request, $project);

        return redirect()->route('admin.projects.index')->with('status', 'Project saved.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->deleteFile($project->image);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Project deleted.');
    }

    protected function save(Request $request, Project $project): void
    {
        $request->validate([
            ...$this->translatedRules(self::TRANSLATED, ['title']),
            ...$this->statsRules(),
            'service_id' => ['nullable', 'exists:services,id'],
            'image' => self::IMAGE_RULES,
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);

        $project->fill([
            ...$this->translatedMany($request, self::TRANSLATED),
            'service_id' => $request->input('service_id') ?: null,
            'stats' => $this->stats($request),
            'image' => $this->upload($request, 'image', $project->image, 'projects'),
            'featured' => $request->boolean('featured'),
            'highlight' => $request->boolean('highlight'),
            'show_on_home' => $request->boolean('show_on_home'),
            'active' => $request->boolean('active'),
            'sort' => (int) $request->input('sort', 0),
        ])->save();

        // Only one project can be the featured one on the home page.
        if ($project->featured) {
            Project::query()->whereKeyNot($project->getKey())->update(['featured' => false]);
        }
    }
}
