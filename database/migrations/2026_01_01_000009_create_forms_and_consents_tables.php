<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('type')->default('intake'); // intake, pre_session, consent, evaluation
            $table->text('description')->nullable();
            $table->json('fields'); // JSON field definitions (name, type, options, required, validation)
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_active')->default(true);
            $table->string('language')->default('en');
            $table->timestamps();
        });

        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('form_template_id')->constrained('form_templates')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('submitted_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('data'); // Submitted responses
            $table->dateTime('submitted_at');
            $table->string('status')->default('completed'); // completed, under_review
            $table->timestamps();

            $table->index(['tenant_id', 'client_id']);
        });

        Schema::create('consent_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('guardian_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('consent_type'); // treatment_consent, privacy_policy, telehealth_consent, minor_assent, data_sharing
            $table->string('version')->default('1.0');
            $table->string('document_title');
            $table->text('document_text');
            $table->string('signer_name');
            $table->text('signature_data')->nullable(); // Base64 signature or hash
            $table->string('ip_address', 45)->nullable();
            $table->string('status')->default('active'); // active, revoked, expired
            $table->dateTime('agreed_at');
            $table->dateTime('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'client_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consent_records');
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_templates');
    }
};
