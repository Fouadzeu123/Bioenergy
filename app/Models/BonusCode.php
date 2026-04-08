<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusCode extends Model
{
    protected $fillable = ['code', 'montant', 'is_active', 'max_usage'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'bonus_user');
    }

    public function getUsageCountAttribute()
    {
        return $this->users()->count();
    }

    public function getRemainingAttribute()
    {
        return $this->max_usage - $this->usage_count;
    }
}
