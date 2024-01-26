<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    

    public function getRouteKeyName() {
        return 'username';
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function branch() {
        return $this->hasOne(Branch::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
}
