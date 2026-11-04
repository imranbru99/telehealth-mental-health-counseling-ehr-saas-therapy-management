<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('practitioner_id')->constrained('practitioners')->cascadeOnDelete();
            $table->string('title');
            $table->text('diagnosis_notes')->nullable(); // Supervised professional notes
            $table->date('start_date');
            $table->date('target_completion_date')->nullable();
            $table->date('review_date')->nullable();
            $table->string('status')->default('active'); // active, reviewing, completed, discontinued
            $table->unsignedTinyInteger('overall_progress_percentage')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'client_id']);
        });

        Schema::create('treatment_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_id')->constrained('treatment_plans')->cascadeOnDelete();
            $table->text('description');
            $table->string('goal_type')->default('practitioner_assigned'); // practitioner_assigned, client_defined
            $table->date('target_date')->nullable();
            $table->string('status')->default('in_progress'); // not_started, in_progress, achieved, discontinued
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->text('clinical_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_goals');
        Schema::dropIfExists('treatment_plans');
    }
};
