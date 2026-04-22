<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
protected $fillable = [
    'username',
    'phone',
    'password',
    'invited_by',
    'account_balance',
    'level',
    'invitation_code',
    'country_code',
    'vip_activated_at',
    'is_banned',
    'withdrawal_password',
    'withdrawal_name',
    'withdrawal_account',
    'withdrawal_method',
    'withdrawal_country',
    'email',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->country_code)) {
                $user->country_code = '237'; // Cameroun par défaut
            }
            if (empty($user->withdrawal_country)) {
                // Déduire le pays à partir de country_code
                $user->withdrawal_country = $user->country_code === '225' ? 'CI' : 'CM';
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function parrain()
{
    return $this->belongsTo(User::class, 'invited_by');
}

public function filleuls()
{
    return $this->hasMany(User::class, 'invited_by');
}

public function orders()
{
    return $this->hasMany(Order::class);
}

public function transactions()
{
    return $this->hasMany(Transaction::class);
}

}
