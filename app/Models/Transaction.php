<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'montant',
        'montant_fcfa',
        'status',
        'operator',
        'gateway',
        'reference',
        'gateway_reference',
        'description',
        'order_id',
        'from_user_id'
    ];

    // Relation avec User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}