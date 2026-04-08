<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'limit_order',
        'period_days',
        'min_amount',
        'rate',
    ];

    // Relation avec les épargnes (si tu crées une table "epargnes")
    public function epargnes()
    {
        return $this->hasMany(Epargne::class);
    }
}