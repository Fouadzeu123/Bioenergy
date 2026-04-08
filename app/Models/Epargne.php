<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epargne extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preservation_id',
        'amount',
        'revenu_attendu',
        'start_date',
        'end_date',
        'status',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preservation()
    {
        return $this->belongsTo(Preservation::class);
    }
}