<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListAppointmentsRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Animal;
use App\Models\Appointment;
use App\TimeOfDay;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function index(ListAppointmentsRequest $request)
    {
        $appointments = Appointment::with(['animal', 'animal.client', 'medic'])
            ->orderBy('preferred_date', 'desc')
            // Date filters
            ->when($request->start, fn (Builder $query) => $query->where('preferred_date', '>=', $request->start))
            ->when($request->end, fn (Builder $query) => $query->where('preferred_date', '<=', Carbon::parse($request->end)->addDay()))
            // Animal type filter
            ->when($request->animalTypes, function (Builder $query) use ($request) {
                $query->whereHas('animal', fn (Builder $animal) => $animal->whereIn('type', $request->animalTypes));
            })
            // -------
            ->paginate(15);

        // TODO Cache animal types
        $animalTypes = Animal::types(approved: false);

        return Inertia::render('dashboard/appointments/Index', [
            'appointments' => AppointmentResource::collection($appointments),
            'animalTypes' => $animalTypes,
        ]);
    }

    public function show(Appointment $appointment)
    {
        $appointment = $appointment->load(['animal', 'animal.client']);

        return Inertia::render('dashboard/appointments/Appointment', [
            'appointment' => $appointment,
            'animalTypes' => Animal::types(approved: false),
            'timesOfDay' => TimeOfDay::selectable(),
        ]);
    }

    public function edit(Appointment $appointment)
    {
        Gate::authorize('update', $appointment);
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
