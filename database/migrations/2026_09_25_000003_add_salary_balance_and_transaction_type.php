<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add dedicated salary_balance to wallets table
        Schema::table('wallets', function (Blueprint $table) {
            if (!Schema::hasColumn('wallets', 'salary_balance')) {
                $table->decimal('salary_balance', 16, 2)->default(0)->after('bonus_balance');
            }
        });

        // 2. Allow 'salary' type and 'salary_balance' wallet_type in transactions
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            try {
                DB::statement("ALTER TABLE transactions MODIFY COLUMN type VARCHAR(32) NOT NULL DEFAULT 'deposit'");
                DB::statement("ALTER TABLE transactions MODIFY COLUMN wallet_type VARCHAR(32) NULL");
            } catch (\Throwable $e) {
                // Ignore if already modified
            }
        }
    }

    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            if (Schema::hasColumn('wallets', 'salary_balance')) {
                $table->dropColumn('salary_balance');
            }
        });
    }
};
