<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Free-form page texts and settings, keyed by the definitions in config/content.php
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('subtitle')->nullable();
            $table->json('summary')->nullable();
            $table->json('description')->nullable();
            $table->json('features')->nullable();
            $table->json('stats')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->boolean('show_on_home')->default(true);
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->json('title');
            $table->json('category')->nullable();
            $table->json('badge')->nullable();
            $table->json('description')->nullable();
            $table->json('stats')->nullable();
            $table->string('image')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('highlight')->default(false);
            $table->boolean('show_on_home')->default(false);
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->boolean('in_marquee')->default(true);
            $table->boolean('in_logo_grid')->default(true);
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('badge')->nullable();
            $table->json('tags')->nullable();
            $table->json('description')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        // Small repeatable blocks (timeline, leaders, fleet, ESG pillars, ...), grouped by `group`
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index();
            $table->string('value')->nullable();
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->json('body')->nullable();
            $table->json('list')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('locale', 5)->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('items');
        Schema::dropIfExists('job_offers');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('services');
        Schema::dropIfExists('contents');
    }
};
