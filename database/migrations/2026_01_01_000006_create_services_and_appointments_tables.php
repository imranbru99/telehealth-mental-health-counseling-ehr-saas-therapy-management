<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_minutes')->default(50);
            $table->decimal('price', 10, 2)->default(100.00);
            $table->string('type')->default('individual'); // individual, couples, family, group, child_adolescent
            $table->string('delivery_mode')->default('hybrid'); // in_person, online_telehealth, hybrid
            $table->string('color')->default('#0f766e');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('practitioner_id')->constrained('practitioners')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('appointment_number')->unique();
            $table->dateTime('scheduled_at');
            $table->unsignedInteger('duration_minutes')->default(50);
            $table->string('status')->default('confirmed'); // pending, confirmed, checked_in, in_progress, completed, cancelled, no_show
            $table->string('delivery_mode')->default('in_person'); // in_person, online_telehealth
            $table->string('telehealth_meeting_url')->nullable();
            $table->string('telehealth_meeting_id')->nullable();
            $table->string('telehealth_meeting_password')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->string('payment_status')->default('unpaid'); // unpaid, partially_paid, paid, refunded
            $table->text('admin_notes')->nullable();
            $table->string('booking_source')->default('online'); // online, receptionist, practitioner, admin
            $table->dateTime('reminder_sent_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'practitioner_id', 'scheduled_at']);
            $table->index(['tenant_id', 'client_id', 'scheduled_at']);
        });

        Schema::create('appointment_waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('practitioner_id')->nullable()->constrained('practitioners')->nullOnDelete();
            $table->date('preferred_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('waiting'); // waiting, contacted, booked, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_waitlists');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
    }
};
