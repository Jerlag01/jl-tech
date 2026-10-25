<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Admin users are created with `php artisan admin:create`.
     */
    public function run(): void
    {
        $this->call(SiteContentSeeder::class);
    }
}
