<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListAppointmentsRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Animal;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function index(ListAppointmentsRequest $request)
    {
        $appointments = Appointment::with(['animal', 'animal.client', 'medic'])
            ->orderBy('preferred_date', 'desc')
            // Date filters
            ->when($request->start, fn (Builder $query) => $query->where('preferred_date', '>=', $request->start))
            ->when($request->end, fn (Builder $query) => $query->where('preferred_date', '<=', $request->end))
            // Animal type filter
            ->when($request->animalTypes, function (Builder $query) use ($request) {
                $query->whereHas('animal', fn (Builder $animal) => $animal->whereIn('type', $request->animalTypes));
            })
            // -------
            ->paginate(15);

        // TODO Cache animal types
        $animalTypes = Animal::types(approved: false);

        return Inertia::render('dashboard/Appointments', [
            'appointments' => AppointmentResource::collection($appointments),
            'animalTypes' => $animalTypes,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Appointment $appointment)
    {
        //
    }

    public function edit(Appointment $appointment)
    {
        //
    }

    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    public function destroy(Appointment $appointment)
    {
        //
    }
}
