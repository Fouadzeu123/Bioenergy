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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Relation avec l'utilisateur
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Type de transaction : dépôt, retrait, achat produit, etc.
            $table->enum('type', ['depot', 'retrait', 'invest','epargne','cadeau', 'bonus','gain_journalier','bonus_journalier','bonus_vip','remboursement_preservation','investissement_preservation','bonus_admin'])->default('depot');

            // Montant de la transaction
            $table->decimal('montant', 15, 2);

            // Statut : en attente, validé, refusé
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');

            // Référence unique (utile pour suivi bancaire ou API)
            $table->string('reference')->unique();

            // Description optionnelle
            $table->text('description')->nullable();

            // Dates
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
