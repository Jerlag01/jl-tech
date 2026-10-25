<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The helpdesk (formerly the separate support-app): tickets, departments, SLA plans, teams,
 * organizations, canned responses and the knowledge base, plus the portal and two-step
 * verification columns on users. Existing website administrators become helpdesk administrators.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('grace_hours');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->nullable()->index();
            $table->string('phone', 50)->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('company')->nullable()->after('name');
            $table->foreignId('organization_id')->nullable()->after('company')->constrained()->nullOnDelete();
            $table->string('phone', 50)->nullable()->after('email');
            $table->string('support_role', 20)->default('customer')->after('role_id')->index();
            $table->boolean('is_active')->default(true)->after('locale');
            $table->text('signature')->nullable()->after('is_active');
            $table->text('app_authentication_secret')->nullable();
            $table->text('app_authentication_recovery_codes')->nullable();
            $table->boolean('has_email_authentication')->default(false);
        });

        DB::table('users')->whereNotNull('role_id')->update(['support_role' => 'admin']);

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('sla_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_public')->default(true);
            $table->text('signature')->nullable();
            $table->timestamps();
        });

        Schema::create('department_user', function (Blueprint $table) {
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['department_id', 'user_id']);
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('lead_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('team_user', function (Blueprint $table) {
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['team_id', 'user_id']);
        });

        Schema::create('help_topics', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('icon', 30)->default('tools');
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->foreignId('sla_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('default_priority', 20)->default('normal');
            $table->boolean('is_public')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->nullable()->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('help_topic_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sla_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject');
            $table->string('priority', 20)->default('normal');
            $table->string('status', 30)->default('open');
            $table->boolean('is_answered')->default(false);
            $table->timestamp('due_at')->nullable();
            $table->string('site_address')->nullable();
            $table->string('source', 20)->default('web');
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'last_activity_at']);
            $table->index(['user_id', 'status']);
            $table->index(['department_id', 'status']);
            $table->index(['status', 'due_at']);
        });

        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->boolean('is_internal')->default(false);
            $table->timestamps();

            $table->index(['ticket_id', 'created_at']);
        });

        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_message_id')->constrained()->cascadeOnDelete();
            $table->string('disk', 20)->default('local');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });

        Schema::create('ticket_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30);
            $table->json('data')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['ticket_id', 'created_at']);
        });

        Schema::create('canned_responses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->foreignId('department_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faq_categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_category_id')->constrained()->cascadeOnDelete();
            $table->json('question');
            $table->string('format', 20)->default('rich');
            $table->json('answer');
            $table->json('attachments')->nullable();
            $table->json('attachment_names')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories');
        Schema::dropIfExists('canned_responses');
        Schema::dropIfExists('ticket_events');
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('help_topics');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('department_user');
        Schema::dropIfExists('departments');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
            $table->dropIndex(['support_role']);
            $table->dropColumn(['company', 'phone', 'support_role', 'is_active', 'signature', 'app_authentication_secret', 'app_authentication_recovery_codes', 'has_email_authentication']);
        });

        Schema::dropIfExists('organizations');
        Schema::dropIfExists('sla_plans');
    }
};
