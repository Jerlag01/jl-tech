<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Support\Icons;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Manages the small repeatable blocks defined in config('site.item_groups').
 */
class ItemController extends AdminController
{
    protected const TRANSLATED = ['title', 'subtitle', 'body', 'list'];

    public function index(string $group): View
    {
        return view('admin.items.index', [
            'group' => $group,
            'config' => $this->group($group),
            'items' => Item::query()->inGroup($group)->ordered()->get(),
        ]);
    }

    public function create(string $group): View
    {
        return view('admin.items.form', [
            'group' => $group,
            'config' => $this->group($group),
            'item' => new Item(['group' => $group, 'active' => true, 'sort' => Item::query()->inGroup($group)->max('sort') + 1]),
        ]);
    }

    public function store(Request $request, string $group): RedirectResponse
    {
        $this->group($group);
        $this->save($request, new Item(['group' => $group]));

        return redirect()->route('admin.items.index', $group)->with('status', 'Block created.');
    }

    public function edit(Item $item): View
    {
        return view('admin.items.form', [
            'group' => $item->group,
            'config' => $this->group($item->group),
            'item' => $item,
        ]);
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $this->save($request, $item);

        return redirect()->route('admin.items.index', $item->group)->with('status', 'Block saved.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $this->deleteFile($item->image);
        $item->delete();

        return redirect()->route('admin.items.index', $item->group)->with('status', 'Block deleted.');
    }

    protected function save(Request $request, Item $item): void
    {
        $fields = $this->group($item->group)['fields'];
        $translated = array_values(array_intersect(self::TRANSLATED, array_keys($fields)));

        $request->validate([
            ...$this->translatedRules($translated, ['title']),
            'value' => ['nullable', 'string', 'max:60'],
            'icon' => ['nullable', Rule::in(Icons::names())],
            'image' => self::IMAGE_RULES,
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);

        $item->fill([
            ...$this->translatedMany($request, $translated),
            'value' => $request->input('value'),
            'icon' => $request->input('icon'),
            'image' => $this->upload($request, 'image', $item->image, 'blocks'),
            'active' => $request->boolean('active'),
            'sort' => (int) $request->input('sort', 0),
        ])->save();
    }

    /**
     * @return array{label: string, page: string, fields: array<string, string>}
     */
    protected function group(string $group): array
    {
        return config("site.item_groups.{$group}") ?? abort(404);
    }
}
