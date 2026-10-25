<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tables for the Filament admin panel: roles with permissions, "who is who", news,
 * footer links and blocked IP addresses. Existing users become administrators.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_super')->default(false);
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        $now = now();

        $adminId = DB::table('roles')->insertGetId([
            'name' => 'Administrator',
            'description' => 'Full access to everything, including users, roles and settings.',
            'is_super' => true,
            'permissions' => json_encode([]),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('roles')->insert([
            'name' => 'Editor',
            'description' => 'Manages the website content, news and contact messages.',
            'is_super' => false,
            'permissions' => json_encode([
                'page_content' => ['view', 'update'],
                'page_blocks' => ['view', 'create', 'update', 'delete'],
                'services' => ['view', 'create', 'update', 'delete'],
                'projects' => ['view', 'create', 'update', 'delete'],
                'posts' => ['view', 'create', 'update', 'delete'],
                'clients' => ['view', 'create', 'update', 'delete'],
                'employees' => ['view', 'create', 'update', 'delete'],
                'job_offers' => ['view', 'create', 'update', 'delete'],
                'contact_messages' => ['view', 'update', 'delete'],
            ]),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('password')->constrained()->nullOnDelete();
            $table->string('locale', 5)->default('nl')->after('role_id');
        });

        DB::table('users')->update(['role_id' => $adminId]);

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('job_title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('photo')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('excerpt')->nullable();
            $table->json('body')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('footer_links', function (Blueprint $table) {
            $table->id();
            $table->json('label');
            $table->string('url', 2048);
            $table->boolean('open_in_new_tab')->default(false);
            $table->boolean('active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 64)->unique();
            $table->text('reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
        Schema::dropIfExists('footer_links');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('employees');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn('locale');
        });

        Schema::dropIfExists('roles');
    }
};
