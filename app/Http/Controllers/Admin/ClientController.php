<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends AdminController
{
    public function index(): View
    {
        return view('admin.clients.index', [
            'clients' => Client::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.clients.form', [
            'client' => new Client(['active' => true, 'in_marquee' => true, 'in_logo_grid' => true, 'sort' => Client::query()->max('sort') + 1]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Client);

        return redirect()->route('admin.clients.index')->with('status', 'Client created.');
    }

    public function edit(Client $client): View
    {
        return view('admin.clients.form', ['client' => $client]);
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $this->save($request, $client);

        return redirect()->route('admin.clients.index')->with('status', 'Client saved.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->deleteFile($client->logo);
        $client->delete();

        return redirect()->route('admin.clients.index')->with('status', 'Client deleted.');
    }

    protected function save(Request $request, Client $client): void
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'url' => ['nullable', 'url', 'max:500'],
            'logo' => self::IMAGE_RULES,
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);

        $client->fill([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'logo' => $this->upload($request, 'logo', $client->logo, 'clients'),
            'in_marquee' => $request->boolean('in_marquee'),
            'in_logo_grid' => $request->boolean('in_logo_grid'),
            'active' => $request->boolean('active'),
            'sort' => (int) $request->input('sort', 0),
        ])->save();
    }
}
