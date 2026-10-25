<?php

namespace App\Support;

use App\Models\Content;
use App\Models\Department;
use App\Models\SlaPlan;

/**
 * Helpdesk behaviour set under Website settings > Helpdesk, stored as content fields
 * ("helpdesk.*" in config/content.php).
 */
class HelpdeskSettings
{
    /**
     * Settings that are on/off switches.
     */
    private const BOOLEANS = ['allow_registration', 'auto_assign_on_reply', 'clients_can_reopen', 'show_knowledge_base'];

    /**
     * @param  string  $key  e.g. "allow_registration" or "default_department_id"
     */
    public function get(string $key): mixed
    {
        $value = Content::raw("helpdesk.{$key}");

        if (in_array($key, self::BOOLEANS, true)) {
            return (bool) $value;
        }

        return filled($value) ? $value : null;
    }

    /**
     * @param  array<string, mixed>  $values  e.g. ['allow_registration' => false]
     */
    public function save(array $values): void
    {
        foreach ($values as $key => $value) {
            Content::put("helpdesk.{$key}", in_array($key, self::BOOLEANS, true) ? ($value ? '1' : '0') : (string) ($value ?? ''));
        }
    }

    public function defaultDepartment(): ?Department
    {
        return Department::query()->find($this->get('default_department_id')) ?? Department::query()->orderBy('id')->first();
    }

    public function defaultSlaPlan(): ?SlaPlan
    {
        return SlaPlan::query()->where('is_active', true)->find($this->get('default_sla_plan_id'));
    }

    public function companyName(): string
    {
        return content('company.name') ?: (string) config('app.name');
    }
}
