<?php

namespace App\Models;

use App\UserType;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, MustVerifyEmail, Notifiable;

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

    #[Scope]
    protected function receptionist(Builder $query): Builder
    {
        return $query->where('type', UserType::Receptionist);
    }

    #[Scope]
    protected function medic(Builder $query): Builder
    {
        return $query->where('type', UserType::Medic);
    }

    public function isReceptionist(): bool
    {
        return $this->type === UserType::Receptionist;
    }

    public function isMedic(): bool
    {
        return $this->type === UserType::Medic;
    }
}
