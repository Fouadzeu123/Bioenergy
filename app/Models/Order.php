<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        "user_id",
        "produit_id",
        "quantity",
        "amount_invested",
        "day_income",
        "start_date",
        "end_date",
        "last_gain_at"
    ];

        // Relation vers l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function produit()
{
    return $this->belongsTo(Produit::class);
}
}
