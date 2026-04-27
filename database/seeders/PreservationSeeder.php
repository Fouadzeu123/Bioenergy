<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Preservation;
use Illuminate\Support\Facades\DB;

class PreservationSeeder extends Seeder
{
    public function run(): void
    {
        // Nettoyer la table avant de re-seeder
        DB::table('preservations')->truncate();

        Preservation::create([
            'name' => 'Épargne 7 jours',
            'description' => 'Produit court terme avec rendement de 10% après 7 jours.',
            'limit_order' => 1,
            'period_days' => 7,
            'min_amount' => 5000,
            'rate' => 10,
        ]);

        Preservation::create([
            'name' => 'Épargne 90 jours',
            'description' => 'Produit moyen terme avec rendement de 320% après 90 jours.',
            'limit_order' => 1,
            'period_days' => 90,
            'min_amount' => 10000,
            'rate' => 320,
        ]);

        Preservation::create([
            'name' => 'Épargne 120 jours',
            'description' => 'Produit long terme avec rendement de 450% après 120 jours.',
            'limit_order' => 1,
            'period_days' => 120,
            'min_amount' => 50000,
            'rate' => 450,
        ]);
    }
}