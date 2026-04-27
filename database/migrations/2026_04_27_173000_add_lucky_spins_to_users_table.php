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
        Schema::table('users', function (Blueprint $group) {
            $group->integer('lucky_spins')->default(0)->after('account_balance');
        });

        Schema::create('lucky_wheel_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('total_spins_global')->default(0);
            $table->timestamps();
        });
        
        // Initialiser la config
        \Illuminate\Support\Facades\DB::table('lucky_wheel_configs')->insert(['total_spins_global' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lucky_spins');
        });
        Schema::dropIfExists('lucky_wheel_configs');
    }
};
