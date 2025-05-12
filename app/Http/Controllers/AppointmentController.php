<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListAppointmentsRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function index(ListAppointmentsRequest $request)
    {
        $query = Appointment::with([
            'animal',
            'animal.client',
            'medic',
        ])
            ->orderBy('preferred_date', 'desc');

        if ($request->start) {
            $query->where('preferred_date', '>=', $request->start);
        }
        if ($request->end) {
            $query->where('preferred_date', '<=', $request->end);
        }

        $appointments = $query->paginate(15);

        return Inertia::render('dashboard/Appointments', [
            'appointments' => AppointmentResource::collection($appointments),
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
