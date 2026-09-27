<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('level_number')->unique();
            $table->string('name', 100);
            $table->unsignedInteger('required_directs');
            $table->decimal('min_investment', 16, 2)->default(50.00);
            $table->decimal('monthly_salary', 16, 2);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'required_directs']);
        });

        // Seed initial default salary levels based on specification
        DB::table('salary_levels')->insert([
            [
                'level_number' => 1,
                'name' => 'Level 1',
                'required_directs' => 5,
                'min_investment' => 50.00,
                'monthly_salary' => 20.00,
                'is_active' => true,
                'description' => '5 qualifying direct members with min $50 investment ($20/month)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level_number' => 2,
                'name' => 'Level 2',
                'required_directs' => 10,
                'min_investment' => 50.00,
                'monthly_salary' => 30.00,
                'is_active' => true,
                'description' => '10 qualifying direct members with min $50 investment ($30/month)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level_number' => 3,
                'name' => 'Level 3',
                'required_directs' => 20,
                'min_investment' => 50.00,
                'monthly_salary' => 50.00,
                'is_active' => true,
                'description' => '20 qualifying direct members with min $50 investment ($50/month)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level_number' => 4,
                'name' => 'Level 4',
                'required_directs' => 50,
                'min_investment' => 50.00,
                'monthly_salary' => 100.00,
                'is_active' => true,
                'description' => '50 qualifying direct members with min $50 investment ($100/month)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level_number' => 5,
                'name' => 'Level 5',
                'required_directs' => 100,
                'min_investment' => 50.00,
                'monthly_salary' => 300.00,
                'is_active' => true,
                'description' => '100 qualifying direct members with min $50 investment ($300/month)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_levels');
    }
};
