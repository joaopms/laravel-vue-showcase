<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'symptoms',
        'preferred_date',
        'preferred_time',
        'animal_age_months',
    ];

    protected $casts = [
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
}
