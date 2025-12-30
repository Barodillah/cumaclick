<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'email_verified_at', 'enabled_ads',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'enabled_ads'      => 'boolean',
    ];

    // relasi ke OTP
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    // mutator password otomatis di-hash
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function shortLinks()
    {
        return $this->hasMany(ShortLink::class);
    }

    public function wallet()
    {
        return $this->hasOne(\App\Models\Wallet::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

}
