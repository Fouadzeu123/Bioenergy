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
    'lucky_spins',
    'role',
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
                // Déduire le pays ISO depuis l'indicatif téléphonique
                $user->withdrawal_country = config('notchpay.phone_to_country.' . $user->country_code, 'CM');
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

    /**
     * Retourne la devise de l'utilisateur selon son pays.
     */
    public function getCurrencyAttribute(): string
    {
        $country = config('notchpay.phone_to_country.' . $this->country_code)
                ?? ($this->withdrawal_country ?: 'CM');
        return config('notchpay.currencies.' . $country, 'XAF');
    }

    /**
     * Retourne le code pays ISO (CM, CI, SN, …)
     */
    public function getCountryIsoAttribute(): string
    {
        return config('notchpay.phone_to_country.' . $this->country_code)
            ?? ($this->withdrawal_country ?: 'CM');
    }
}
