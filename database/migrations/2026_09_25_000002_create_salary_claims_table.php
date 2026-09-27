<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('salary_level_id')->constrained('salary_levels')->cascadeOnDelete();
            $table->unsignedInteger('level_number');
            $table->unsignedInteger('required_directs');
            $table->unsignedInteger('qualifying_directs');
            $table->decimal('amount', 16, 2);
            $table->string('claim_period', 20); // e.g. "2026-09"
            $table->timestamp('claimed_at');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('completed');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            // Physical database duplicate claim protection: only 1 claim per user per monthly period
            $table->unique(['user_id', 'claim_period'], 'unique_user_claim_period');
            $table->index(['user_id', 'claimed_at']);
            $table->index(['claim_period', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_claims');
    }
};
