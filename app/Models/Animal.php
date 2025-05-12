<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public static function types(bool $approved): array
    {
        return DB::table('animals')
            ->join('appointments', 'animals.id', '=', 'appointments.animal_id')
            ->when($approved, fn (Builder $query) => $query->whereNotNull('appointments.medic_id'))
            ->select('animals.type')
            ->distinct()
            ->pluck('animals.type')
            ->toArray();
    }
}
