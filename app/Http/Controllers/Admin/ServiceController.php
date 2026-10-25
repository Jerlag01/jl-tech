<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Support\Icons;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends AdminController
{
    protected const TRANSLATED = ['title', 'subtitle', 'summary', 'description', 'features'];

    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::query()->ordered()->withCount('projects')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.form', [
            'service' => new Service(['active' => true, 'show_on_home' => true, 'sort' => Service::query()->max('sort') + 1]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $service = new Service;
        $this->save($request, $service);

        return redirect()->route('admin.services.index')->with('status', 'Service created.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', ['service' => $service]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->save($request, $service);

        return redirect()->route('admin.services.index')->with('status', 'Service saved.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->deleteFile($service->image);
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Service deleted.');
    }

    protected function save(Request $request, Service $service): void
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('title.en') ?: $request->input('title.nl'))]);

        $request->validate([
            ...$this->translatedRules(self::TRANSLATED, ['title']),
            ...$this->statsRules(),
            'slug' => ['required', 'string', 'max:120', Rule::unique('services')->ignore($service)],
            'icon' => ['nullable', Rule::in(Icons::names())],
            'image' => self::IMAGE_RULES,
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);

        $service->fill([
            ...$this->translatedMany($request, self::TRANSLATED),
            'slug' => $request->input('slug'),
            'stats' => $this->stats($request),
            'icon' => $request->input('icon'),
            'image' => $this->upload($request, 'image', $service->image, 'services'),
            'show_on_home' => $request->boolean('show_on_home'),
            'active' => $request->boolean('active'),
            'sort' => (int) $request->input('sort', 0),
        ])->save();
    }
}
