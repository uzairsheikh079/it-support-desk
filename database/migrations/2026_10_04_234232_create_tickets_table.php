<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->string('subject', 120);
            $table->text('description');
            $table->string('requester_name', 100);
            $table->string('requester_email');
            $table->string('priority', 20)->default('medium');
            $table->string('status', 30)->default('open');
            $table->string('assigned_to', 100)->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['priority', 'created_at']);
            $table->index('requester_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
