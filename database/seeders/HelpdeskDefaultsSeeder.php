<?php

namespace Database\Seeders;

use App\Enums\TicketPriority;
use App\Models\Content;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\SlaPlan;
use Illuminate\Database\Seeder;

/**
 * Starting departments, SLA plans and help topics for the helpdesk. Safe to run again:
 * existing records are left alone. Everything can be changed in /admin > Helpdesk.
 */
class HelpdeskDefaultsSeeder extends Seeder
{
    public function run(): void
    {
        $standard = SlaPlan::query()->firstOrCreate(['name' => 'Standaard (48u)'], ['grace_hours' => 48]);
        $urgent = SlaPlan::query()->firstOrCreate(['name' => 'Dringend (4u)'], ['grace_hours' => 4]);

        $support = Department::query()->firstOrCreate(['name' => 'Support'], ['sla_plan_id' => $standard->id]);
        $service = Department::query()->firstOrCreate(['name' => 'Service & onderhoud'], ['sla_plan_id' => $standard->id]);
        $administration = Department::query()->firstOrCreate(['name' => 'Administratie'], ['sla_plan_id' => $standard->id]);

        $topics = [
            ['malfunction', 'bolt', $service, $urgent, TicketPriority::High],
            ['maintenance', 'tools', $service, null, TicketPriority::Normal],
            ['installation', 'construction', $support, null, TicketPriority::Normal],
            ['quote', 'file', $support, null, TicketPriority::Low],
            ['billing', 'receipt', $administration, null, TicketPriority::Normal],
            ['other', 'chat', $support, null, TicketPriority::Normal],
        ];

        foreach ($topics as $order => [$key, $icon, $department, $slaPlan, $priority]) {
            $name = $this->translations("support.categories.{$key}");

            if (HelpTopic::query()->where('name->nl', $name['nl'])->exists()) {
                continue;
            }

            HelpTopic::query()->create([
                'name' => $name,
                'description' => $this->translations("support.category_descriptions.{$key}"),
                'icon' => $icon,
                'department_id' => $department->id,
                'sla_plan_id' => $slaPlan?->id,
                'default_priority' => $priority,
                'sort_order' => $order,
            ]);
        }

        if (blank(Content::raw('helpdesk.default_department_id'))) {
            Content::put('helpdesk.default_department_id', (string) $support->id);
            Content::put('helpdesk.default_sla_plan_id', (string) $standard->id);
        }
    }

    /**
     * @return array<string, string>
     */
    private function translations(string $key): array
    {
        return collect(config('site.locales'))->keys()->mapWithKeys(fn (string $locale): array => [$locale => __($key, [], $locale)])->all();
    }
}
