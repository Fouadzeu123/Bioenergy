<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Preservation;

class PreservationSeeder extends Seeder
{
    public function run(): void
    {
        Preservation::create([
            'name' => 'Épargne 7 jours',
            'description' => 'Produit court terme avec rendement de 10% après 7 jours.',
            'limit_order' => 1,
            'period_days' => 7,
            'min_amount' => 10,
            'rate' => 10,
        ]);

        Preservation::create([
            'name' => 'Épargne 90 jours',
            'description' => 'Produit moyen terme avec rendement de 360% après 90 jours.',
            'limit_order' => 1,
            'period_days' => 90,
            'min_amount' => 20,
            'rate' => 320,
        ]);

        Preservation::create([
            'name' => 'Épargne 120 jours',
            'description' => 'Produit long terme avec rendement de 450% après 120 jours.',
            'limit_order' => 1,
            'period_days' => 120,
            'min_amount' => 100,
            'rate' => 450,
        ]);
    }
}