<?php

namespace App\Models;

use App\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'type' => UserType::class,
        ];
    }

    public function medicAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'medic_id');
    }

    public function receptionistAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'receptionist_id');
    }
}
