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
        Schema::create('epargnes', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('preservation_id')->constrained()->onDelete('cascade');

            // Montant et revenu
            $table->integer('amount'); // montant épargné
            $table->decimal('revenu_attendu', 12, 2); // revenu calculé

            // Dates
            $table->date('start_date');
            $table->date('end_date');

            // Statut de l’épargne
            $table->enum('status', ['en_cours', 'terminee', 'retiree'])->default('en_cours');

            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epargnes');
    }
};
