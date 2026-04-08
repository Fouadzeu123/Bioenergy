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
        Schema::create('preservations', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // Nom du produit (ex: "Épargne 7 jours")
    $table->text('description')->nullable();
    $table->integer('limit_order')->default(1); // Limite d'achat par utilisateur
    $table->integer('period_days'); // 7, 21, 45 jours
    $table->integer('min_amount'); // Montant minimum requis (5000, 10000, 20000)
    $table->decimal('rate', 5, 2); // Taux de rendement en % (3.5, 20, 60)
    $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preservations');
    }
};
