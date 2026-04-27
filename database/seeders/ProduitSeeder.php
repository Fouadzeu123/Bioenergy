<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;

class ProduitSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        // Supprimer les produits existants pour repartir sur de bonnes bases
        Produit::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        // Pack Solaire
        Produit::create([
            'name' => 'Pack Solaire',
            'description' => 'Investissez dans l’énergie solaire pour un avenir durable.',
            'min_amount' => 5000,
            'max_amount' => 100000,
            'level'=>1,
            'rate' => 4,
            'limit_order' => 5,
            'information' => <<<'EOT'
Le Pack Solaire permet d'investir directement dans des installations photovoltaïques résidentielles et communautaires.
Rendement fixe de 4% appliqué sur le capital investi, calculé et crédité selon la fréquence définie par la plateforme.
Montant recommandé pour ce produit : entre 5 000 FCFA et 100 000 FCFA, idéal pour investisseurs confirmés.
Les fonds sont utilisés pour l'achat de panneaux, onduleurs et installation, avec suivi des performances par site.
Maintenance et garanties techniques sont prises en charge par nos partenaires locaux certifiés.
Rapports réguliers (mensuels/trimestriels) sur la production d'énergie et le rendement financier.
Possibilité de réinvestir automatiquement les gains pour profiter de l'effet composé.
Processus d'achat simple, validation rapide et assistance dédiée pour l'installation.
Transparence : accès aux documents techniques et aux contrats de maintenance.
Produit conçu pour concilier impact environnemental et rendement financier stable.
EOT
        ]);

        // Éolienne Domestique
        Produit::create([
            'name' => 'Éolienne Domestique',
            'description' => 'Une solution innovante pour produire de l’électricité chez soi.',
            'min_amount' => 101000,
            'max_amount' => 500000,
            'rate' => 4.5,
             'level'=>2,
            'limit_order' => 2,
            'information' => <<<'EOT'
L'Éolienne Domestique finance l'installation de petites éoliennes adaptées aux zones rurales et périurbaines.
Taux attractif de 4.5% pour les investissements entre 101 000 FCFA et 500 000 FCFA, conçu pour investisseurs sérieux.
Les fonds servent à l'achat d'éoliennes, fondations, câblage et raccordement au réseau local.
Chaque projet est évalué selon l'ensoleillement et le vent local ; études de site incluses dans le dossier.
Maintenance préventive et interventions techniques assurées par nos équipes partenaires.
Suivi de production en temps réel via tableau de bord et rapports de performance périodiques.
Option de co-investissement pour projets communautaires à plus grande échelle.
Assurance et garanties sur matériel et production pour sécuriser l'investissement.
Accès prioritaire aux nouvelles opportunités et conditions commerciales personnalisées.
Conçu pour maximiser la production d'énergie renouvelable tout en offrant un rendement stable.
EOT
        ]);

        // Projet Biomasse
        Produit::create([
            'name' => 'Projet Biomasse',
            'description' => 'Transformez les déchets organiques en énergie verte.',
            'min_amount' => 501000,
            'max_amount' => 5000000,
            'rate' => 5.5,
             'level'=>3,
            'limit_order' => 2,
            'information' => <<<'EOT'
Le Projet Biomasse finance des unités de valorisation des déchets organiques en énergie (biomasse).
Taux premium de 5.5% pour investissements de 501 000 FCFA à 5 000 000 FCFA, adapté aux investisseurs institutionnels et grands comptes.
Les fonds sont alloués à la construction d'unités, à l'achat d'équipements et à la logistique de collecte.
Impact environnemental fort : réduction des déchets, production d'énergie locale et fertilisants organiques.
Études d'impact et business plan détaillé fournis avant chaque lancement de projet.
Partenariats locaux pour la collecte des matières premières et la distribution de l'énergie produite.
Reporting financier et opérationnel complet, avec audits réguliers et indicateurs de performance.
Possibilité de co-investissement et de conditions contractuelles sur-mesure selon le volume.
Services annexes : formation des équipes locales, maintenance et optimisation des installations.
Produit conçu pour combiner rendement élevé et contribution tangible à l'économie circulaire.
EOT
        ]);

        // Projet BioGaz
        Produit::create([
            'name' => 'Projet BioGaz',
            'description' => 'Production de biogaz à partir de déchets organiques pour une énergie propre.',
            'min_amount' => 5001000,
            'max_amount' => 25000000,
            'rate' => 6,
             'level'=>4,
            'limit_order' => 1,
            'information' => <<<'EOT'
Le Projet BioGaz vise à installer des digesteurs anaérobies pour produire du biogaz et de l'engrais organique.
Taux de 6% pour investissements de 5 001 000 FCFA à 25 000 000 FCFA, destiné aux investisseurs cherchant impact et rendement.
Les fonds couvrent la construction, l'équipement, la logistique et la mise en service des unités de digestion.
Le biogaz produit peut alimenter des micro-réseaux, des industries locales ou être converti en électricité.
Valorisation des sous-produits : digestat utilisé comme fertilisant, créant une source de revenus additionnelle.
Études techniques et environnementales réalisées avant chaque projet pour garantir viabilité et conformité.
Contrats d'approvisionnement et partenariats locaux assurent la disponibilité continue de matière première.
Suivi opérationnel et reporting financier détaillé pour assurer transparence et traçabilité.
Possibilité d'optimisation des rendements via technologies complémentaires (co-génération, stockage).
Produit pensé pour répondre aux besoins énergétiques locaux tout en générant des revenus durables.
EOT
        ]);

        // Projet Biocarburants
        Produit::create([
            'name' => 'Projet Biocarburants',
            'description' => 'Production de carburants renouvelables à partir de matières organiques.',
            'min_amount' => 25001000,
            'max_amount' => null,
            'rate' => 7,
             'level'=>5,
            'limit_order' => 5,
            'information' => <<<'EOT'
Le Projet Biocarburants finance des installations de production de carburants renouvelables (biodiesel, bioéthanol).
Taux de 7% pour investissements supérieurs à 25 000 000 FCFA, adapté aux investisseurs stratégiques.
Les fonds servent à l'acquisition d'équipements, à la transformation et à la distribution des biocarburants.
Impact positif : réduction des émissions fossiles et création de valeur locale à partir de cultures ou déchets.
Études de marché et partenariats industriels garantissent débouchés et intégration dans la chaîne logistique.
Contrats d'approvisionnement sécurisés et accords commerciaux pour la vente des produits finis.
Reporting technique et financier détaillé, avec audits réguliers et indicateurs de performance.
Possibilité d'optimisation fiscale et de structuration selon le volume d'investissement.
Programme R&D pour améliorer les rendements et réduire les coûts opérationnels.
Produit conçu pour allier transition énergétique, création d'emplois locaux et rendement attractif.
EOT
        ]);
    }
}
