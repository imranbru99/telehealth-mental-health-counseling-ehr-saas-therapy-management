<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('therapy_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('practitioner_id')->constrained('practitioners')->cascadeOnDelete();
            $table->string('session_number')->unique();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->unsignedInteger('duration_minutes')->default(50);
            $table->string('session_type')->default('individual'); // individual, couples, family, group, child_adolescent, online, in_person
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->string('documentation_status')->default('pending'); // pending, draft, signed, locked
            $table->string('billing_status')->default('pending'); // pending, invoiced, paid
            $table->timestamps();

            $table->index(['tenant_id', 'practitioner_id', 'status']);
        });

        Schema::create('clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('therapy_session_id')->constrained('therapy_sessions')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('practitioner_id')->constrained('practitioners')->cascadeOnDelete();
            $table->string('format')->default('SOAP'); // SOAP, DAP, BIRP, GIRP, PROGRESS, INITIAL_ASSESSMENT, DISCHARGE_SUMMARY
            $table->text('summary')->nullable();
            $table->json('content'); // Structured template fields (e.g. subjective, objective, assessment, plan)
            $table->boolean('is_signed')->default(false);
            $table->dateTime('signed_at')->nullable();
            $table->foreignId('signed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('signature_hash')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('parent_note_id')->nullable()->constrained('clinical_notes')->nullOnDelete();
            $table->timestamps();

            $table->index(['tenant_id', 'client_id']);
            $table->index(['tenant_id', 'practitioner_id']);
        });

        Schema::create('clinical_note_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_note_id')->constrained('clinical_notes')->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->json('content');
            $table->text('amendment_reason');
            $table->foreignId('amended_by_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('clinical_note_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('clinical_note_id')->constrained('clinical_notes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action'); // view, create, edit, sign, lock, export, print
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['tenant_id', 'clinical_note_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_note_access_logs');
        Schema::dropIfExists('clinical_note_versions');
        Schema::dropIfExists('clinical_notes');
        Schema::dropIfExists('therapy_sessions');
    }
};
