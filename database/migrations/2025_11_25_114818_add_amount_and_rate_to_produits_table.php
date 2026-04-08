<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountAndRateToProduitsTable extends Migration
{
    public function up()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->decimal('min_amount', 15, 2)->nullable()->after('description');
            $table->decimal('max_amount', 15, 2)->nullable()->after('min_amount');
            $table->decimal('rate', 5, 2)->nullable()->after('max_amount'); // pourcentage, ex: 2.00
        });
    }

    public function down()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['min_amount', 'max_amount', 'rate','price']);
        });
    }
}