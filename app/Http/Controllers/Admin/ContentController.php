<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends AdminController
{
    public function edit(string $group): View
    {
        return view('admin.content.edit', [
            'group' => $group,
            'config' => $this->group($group),
        ]);
    }

    public function update(Request $request, string $group): RedirectResponse
    {
        $fields = $this->group($group)['fields'];

        $request->validate($this->rules($fields));

        foreach ($fields as $key => $field) {
            $input = $this->inputName($key);

            $value = match (true) {
                $field['type'] === 'image' => $this->upload($request, $input, Content::text($key) ?: null, 'content') ?? '',
                $field['type'] === 'boolean' => $request->boolean($input) ? '1' : '0',
                $field['tr'] ?? false => $this->translated($request, $input),
                default => trim((string) $request->input($input, '')),
            };

            Content::put($key, $value);
        }

        return back()->with('status', 'Changes saved.');
    }

    /**
     * Field keys contain dots, which Laravel reads as nesting; inputs use underscores instead.
     */
    public static function inputName(string $key): string
    {
        return str_replace('.', '__', $key);
    }

    /**
     * @param  array<string, array<string, mixed>>  $fields
     * @return array<string, mixed>
     */
    protected function rules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $key => $field) {
            $input = $this->inputName($key);

            if ($field['tr'] ?? false) {
                $rules += $this->translatedRules([$input]);

                continue;
            }

            $rules[$input] = match ($field['type']) {
                'image' => self::IMAGE_RULES,
                'boolean' => ['nullable', 'boolean'],
                'email' => ['nullable', 'email', 'max:190'],
                'url' => ['nullable', 'url', 'max:500'],
                'number' => ['nullable', 'numeric'],
                default => ['nullable', 'string', 'max:20000'],
            };
        }

        return $rules;
    }

    /**
     * @return array{label: string, fields: array<string, array<string, mixed>>, settings?: bool, page?: string}
     */
    protected function group(string $group): array
    {
        return config("content.{$group}") ?? abort(404);
    }
}
