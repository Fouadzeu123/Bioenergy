<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        "name",
        "description",
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
