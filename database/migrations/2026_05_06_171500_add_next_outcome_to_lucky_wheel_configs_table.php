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
        Schema::table('lucky_wheel_configs', function (Blueprint $table) {
            $table->integer('next_outcome')->nullable()->after('total_spins_global');
            $table->integer('default_prize')->default(500)->after('next_outcome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lucky_wheel_configs', function (Blueprint $table) {
            $table->dropColumn(['next_outcome', 'default_prize']);
        });
    }
};
