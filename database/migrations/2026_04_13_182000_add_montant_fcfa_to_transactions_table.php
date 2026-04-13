<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute les colonnes nécessaires pour l'intégration Notch Pay :
 *   - montant_fcfa : montant en Franc CFA (XAF) pour l'appel API
 *
 * La colonne gateway_reference existait déjà (via migration précédente).
 * La colonne operator et gateway existaient déjà.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'montant_fcfa')) {
                $table->unsignedBigInteger('montant_fcfa')->nullable()->after('montant');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'montant_fcfa')) {
                $table->dropColumn('montant_fcfa');
            }
        });
    }
};
