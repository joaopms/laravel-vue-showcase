<?php

namespace App\Models;

use App\Helpers\AgeParser;
use App\TimeOfDay;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'symptoms',
        'preferred_date',
        'preferred_time',
        'animal_age_months',
        'medic_id',
        'receptionist_id',
    ];

    protected $dateFormat = 'Y-m-d';

    protected $casts = [
        'preferred_date' => 'date:Y-m-d',
        'preferred_time' => TimeOfDay::class,
        'assigned_at' => 'timestamp',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function receptionist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receptionist_id');
    }

    public function medic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medic_id');
    }

    public function client(): HasOneThrough
    {
        return $this->hasOneThrough(
            Client::class,
            Animal::class,
            'client_id', // FK on Animal
            'id', // FK on Client
        );
    }

    #[Scope]
    protected function assigned(Builder $query): Builder
    {
        return $query->whereNotNull('medic_id');
    }

    public function preferredDateFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->preferred_date->format('d/m/Y')
        );
    }

    public function preferredTimeFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->preferred_time->format()
        );
    }

    public function animalAge(): Attribute
    {
        return Attribute::make(
            get: fn () => new AgeParser($this->animal_age_months)
        );
    }
}
