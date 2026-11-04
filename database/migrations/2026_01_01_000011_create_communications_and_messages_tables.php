<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject')->nullable();
            $table->text('body');
            $table->boolean('is_clinical')->default(false); // Sensitive clinical messaging requiring strict access
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'recipient_id', 'is_read']);
            $table->index(['tenant_id', 'sender_id']);
        });

        Schema::create('notifications_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->default('email'); // email, sms, in_app, whatsapp
            $table->string('event'); // appointment_reminder, booking_confirmed, etc.
            $table->string('recipient');
            $table->string('subject')->nullable();
            $table->text('content');
            $table->string('status')->default('sent'); // sent, failed, queued
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_log');
        Schema::dropIfExists('messages');
    }
};
