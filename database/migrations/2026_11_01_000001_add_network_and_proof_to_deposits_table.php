<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'network')) {
                $table->string('network')->nullable()->after('txid');
            }
            if (!Schema::hasColumn('deposits', 'proof_path')) {
                $table->string('proof_path')->nullable()->after('network');
            }
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumnIfExists('network');
            $table->dropColumnIfExists('proof_path');
        });
    }
};
