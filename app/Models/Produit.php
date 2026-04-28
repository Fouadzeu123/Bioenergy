<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        "name",
        "image",
        "description",
        "min_amount",
        "max_amount",
        "rate",
        "price",
        "limit_order",
        "day_income",
        "level",
    ];
    public function orders()
{
    return $this->hasMany(Order::class);
}
}
