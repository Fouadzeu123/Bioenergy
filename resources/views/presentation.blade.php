<x-layouts :title="'Présentation de l\'Entreprise'" :level="'Public'">

    <!-- Bannière -->
    <div class="w-full relative">
        <img src="{{ asset('images/presentation.jpg') }}"
             alt="Présentation BioEnergy"
             class="w-full h-72 object-cover shadow-lg rounded-lg">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-black/40 rounded-lg px-6 py-3">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white">BioEnergy</h1>
                <p class="text-sm sm:text-base text-white/90 mt-1">Investir dans l'énergie propre pour un avenir durable</p>
            </div>
        </div>
    </div>

    <!-- Résumé rapide -->
    <section class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-green-700 mb-2">Qui sommes-nous</h2>
            <p class="text-gray-700 leading-relaxed">
                BioEnergy développe et finance des projets d’énergies renouvelables (solaire, biomasse, éolien)
                en associant impact environnemental et rendement financier. Nous permettons aux particuliers et
                aux entreprises d’investir directement dans des actifs réels et productifs.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-blue-700 mb-2">Chiffres clés</h2>
            <ul class="text-gray-700 space-y-2">
                <li><span class="font-semibold">Projets lancés :</span> 48</li>
                <li><span class="font-semibold">Investisseurs :</span> 12 400+</li>
                <li><span class="font-semibold">Énergie produite :</span> 18 500 MWh/an</li>
                <li><span class="font-semibold">Réduction CO₂ estimée :</span> 9 200 tonnes/an</li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-yellow-700 mb-2">Notre promesse</h2>
            <p class="text-gray-700 leading-relaxed">
                Transparence, sécurité et impact. Nous publions des rapports réguliers, assurons la maintenance
                des installations et redistribuons une part des revenus aux investisseurs sous forme de gains journaliers.
            </p>
        </div>
    </section>

    <!-- Histoire et jalons -->
    <section class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-green-700 mb-4">Historique et jalons</h2>

        <div class="space-y-6">
            <div class="flex gap-4 items-start">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-700 font-bold">2016</div>
                <div>
                    <h3 class="font-semibold text-gray-800">Fondation</h3>
                    <p class="text-gray-600">BioEnergy est fondée par un groupe d’ingénieurs et d’entrepreneurs avec l’objectif de développer des solutions énergétiques locales et durables.</p>
                </div>
            </div>

            <div class="flex gap-4 items-start">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-700 font-bold">2018</div>
                <div>
                    <h3 class="font-semibold text-gray-800">Premier parc solaire</h3>
                    <p class="text-gray-600">Mise en service du premier parc solaire communautaire, fournissant de l’électricité à plus de 1 200 foyers.</p>
                </div>
            </div>

            <div class="flex gap-4 items-start">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-700 font-bold">2020</div>
                <div>
                    <h3 class="font-semibold text-gray-800">Diversification</h3>
                    <p class="text-gray-600">Lancement des projets biomasse et biogaz pour valoriser les déchets agricoles et industriels.</p>
                </div>
            </div>

            <div class="flex gap-4 items-start">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-700 font-bold">2022</div>
                <div>
                    <h3 class="font-semibold text-gray-800">Plateforme d’investissement</h3>
                    <p class="text-gray-600">Ouverture de la plateforme d’investissement participatif permettant aux particuliers d’acheter des parts de projets.</p>
                </div>
            </div>

            <div class="flex gap-4 items-start">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-700 font-bold">2024</div>
                <div>
                    <h3 class="font-semibold text-gray-800">Croissance et impact</h3>
                    <p class="text-gray-600">Atteinte de 10 000 investisseurs et extension des opérations dans plusieurs régions, avec renforcement des partenariats locales.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Équipe fondatrice et gouvernance -->
    <section class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-blue-700 mb-3">Équipe fondatrice</h2>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center font-semibold">AB</div>
                    <div>
                        <div class="font-semibold">Amina B.</div>
                        <div class="text-xs text-gray-500">CEO & co-fondatrice</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center font-semibold">MK</div>
                    <div>
                        <div class="font-semibold">Marc K.</div>
                        <div class="text-xs text-gray-500">CTO & co-fondateur</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center font-semibold">LN</div>
                    <div>
                        <div class="font-semibold">Lina N.</div>
                        <div class="text-xs text-gray-500">Directrice des opérations</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-green-700 mb-3">Conseil d’administration</h2>
            <p class="text-gray-700">Composé d’experts en énergie, finance et développement durable, le conseil assure la gouvernance et la stratégie à long terme.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-yellow-700 mb-3">Partenaires stratégiques</h2>
            <ul class="text-gray-700 space-y-2">
                <li>Institutions financières locales</li>
                <li>Fournisseurs d’équipements certifiés</li>
                <li>ONGs et collectivités locales</li>
            </ul>
        </div>
    </section>

    <!-- Produits et cas d'usage détaillés -->
    <section class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-purple-700 mb-4">Produits et cas d'usage</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-green-600 mb-2">Pack Solaire</h3>
                <p class="text-gray-700 text-sm mb-2">Installation de panneaux photovoltaïques pour foyers et petites entreprises.</p>
                <ul class="text-gray-700 text-sm space-y-1">
                    <li>Réduction facture électrique</li>
                    <li>Production locale d’énergie</li>
                    <li>Maintenance incluse</li>
                </ul>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-yellow-600 mb-2">Projet Biomasse</h3>
                <p class="text-gray-700 text-sm mb-2">Valorisation des déchets agricoles en énergie et fertilisant.</p>
                <ul class="text-gray-700 text-sm space-y-1">
                    <li>Création de valeur locale</li>
                    <li>Réduction des émissions</li>
                    <li>Revenus complémentaires</li>
                </ul>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h3 class="font-semibold text-blue-600 mb-2">Éolienne Domestique</h3>
                <p class="text-gray-700 text-sm mb-2">Petites éoliennes pour zones rurales et sites isolés.</p>
                <ul class="text-gray-700 text-sm space-y-1">
                    <li>Complément au solaire</li>
                    <li>Résilience énergétique</li>
                    <li>Suivi en temps réel</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Impact social et environnemental -->
    <section class="mt-8 bg-green-50 rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-green-700 mb-4">Impact social et environnemental</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-800">Emplois locaux</h3>
                <p class="text-gray-700">Nos projets créent des emplois directs et indirects (installation, maintenance, collecte de biomasse).</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Réduction des émissions</h3>
                <p class="text-gray-700">Chaque projet est évalué pour estimer la réduction de CO₂ et l’impact sur la qualité de l’air.</p>
            </div>
        </div>
    </section>

    <!-- Transparence et rapports -->
    <section class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-blue-700 mb-4">Transparence et rapports</h2>
        <p class="text-gray-700 mb-3">Nous publions des rapports opérationnels et financiers pour chaque projet :</p>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Rapports mensuels de production et revenus</li>
            <li>Audits annuels indépendants</li>
            <li>Documents techniques et contrats de maintenance</li>
        </ul>
    </section>

    <!-- FAQ -->
    <section class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-green-700 mb-4">FAQ</h2>

        <div class="space-y-4">
            <div>
                <h4 class="font-semibold">Comment investir ?</h4>
                <p class="text-gray-700">Créez un compte, choisissez un produit et investissez le montant souhaité. Les gains sont calculés et crédités selon la durée du produit.</p>
            </div>

            <div>
                <h4 class="font-semibold">Quel est le risque ?</h4>
                <p class="text-gray-700">Comme tout investissement, il existe des risques opérationnels et de marché. Nous limitons ces risques par des études de projet, des partenaires fiables et une diversification.</p>
            </div>

            <div>
                <h4 class="font-semibold">Comment sont distribués les gains ?</h4>
                <p class="text-gray-700">Les revenus générés par les projets sont redistribués aux investisseurs sous forme de gains journaliers et de bonus selon les règles du produit.</p>
            </div>
        </div>
    </section>

    <!-- Appel à l'action -->
    <section class="mt-8 bg-gradient-to-r from-green-600 to-blue-600 rounded-lg shadow-md p-6 text-white text-center">
        <h2 class="text-2xl font-bold mb-2">Prêt à investir dans l'énergie propre ?</h2>
        <p class="mb-4">Rejoignez notre communauté d'investisseurs et participez à des projets concrets qui transforment les territoires.</p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('products') }}" class="bg-white text-green-700 px-5 py-2 rounded-lg font-semibold shadow hover:opacity-90">Voir les produits</a>
            <a href="{{ route('contact') }}" class="bg-white/20 border border-white px-5 py-2 rounded-lg font-semibold hover:bg-white/10">Nous contacter</a>
        </div>
    </section>

    <!-- Contact détaillé -->
    <section class="mt-8 bg-white rounded-lg shadow-md p-6 text-center">
        <h2 class="text-2xl font-bold text-red-700 mb-4">Contact</h2>
        <p class="text-gray-700 mb-2">Email : contact@bioenergy.com</p>
        <p class="text-gray-700 mb-2">Téléphone : +1(232)27815376</p>
        <p class="text-gray-700">Adresse :7440 E Pinnacle Peak Rd, Scottsdale, AZ 85255</p>
    </section>

</x-layouts>