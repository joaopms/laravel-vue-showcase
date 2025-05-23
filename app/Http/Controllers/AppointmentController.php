<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListAppointmentsRequest;
use App\Http\Requests\StoreAppointmentSchedule;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\UserResource;
use App\Models\Animal;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Notifications\AppointmentAssigned;
use App\TimeOfDay;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class AppointmentController extends Controller
{
    public function index(ListAppointmentsRequest $request)
    {
        $user = $request->user();
        $chooseShowAll = $user->can('choose-show-all', Appointment::class);
        $appointments = Appointment::with(['animal', 'animal.client', 'medic'])
            ->orderBy('preferred_date', 'desc')
            // Date filters
            ->when($request->start, fn (Builder $query) => $query->where('preferred_date', '>=', $request->start))
            ->when($request->end, fn (Builder $query) => $query->where('preferred_date', '<=', Carbon::parse($request->end)->addDay()))
            // Animal type filter
            ->when($request->animalTypes, function (Builder $query) use ($request) {
                $query->whereHas('animal', fn (Builder $animal) => $animal->whereIn('type', $request->animalTypes));
            })
            // Show all filter (defaults to false for medics)
            ->when(
                $chooseShowAll && ! $request->showAll,
                fn (Builder $query) => $query->where('medic_id', $user->id)
            )
            // -------
            ->paginate(15);

        // TODO Cache animal types
        $animalTypes = Animal::types(approved: false);

        return Inertia::render('dashboard/appointments/Index', [
            'appointments' => new AppointmentCollection($appointments)->listing(),
            'animalTypes' => $animalTypes,
            '_can' => ['chooseShowAll' => $chooseShowAll],
        ]);
    }

    public function show(Appointment $appointment)
    {
        $appointment = $appointment->load(['animal', 'animal.client']);
        $medics = User::medic()->get();

        return Inertia::render('dashboard/appointments/Appointment', [
            'appointment' => new AppointmentResource($appointment)->showing(),
            'animalTypes' => Animal::types(approved: false),
            'timesOfDay' => TimeOfDay::selectable(),
            'medics' => UserResource::collection($medics),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(StoreAppointmentSchedule $request, Appointment $appointment)
    {
        $previouslyAssigned = (bool) $appointment->assigned_at;
        DB::transaction(function () use ($request, $appointment) {
            // Update the client
            $data = $request->safe();
            $client = Client::updateOrCreate(
                ['email' => $data['client.email']],
                $data['client'],
            );

            // Update the animal
            $appointment->animal()->update([
                ...$request->getAnimalData(),
                // If the client ID has changed, update the animal
                'client_id' => $client->id,
            ]);

            // Update the appointment
            $canAssign = $request->user()->can('assign', Appointment::class);
            $assignData = $canAssign ? [
                'medic_id' => $data['medic.id'],
                'receptionist_id' => $request->user()->id,
            ] : [];
            $appointment->update([
                ...$request->getAppointmentData(),
                ...$assignData,
            ]);
        });

        // Notify user
        $firstAssign = $appointment->assigned_at && ! $previouslyAssigned;
        if ($firstAssign) {
            $appointment->client->notify(new AppointmentAssigned($appointment));
        }

        $request->session()->flash('success', 'Appointment was successfully edited');

        return to_route('dashboard.appointments.index');
    }

    /**
     * @throws Throwable
     */
    public function destroy(Request $request, Appointment $appointment)
    {
        DB::transaction(function () use ($appointment) {
            $animal = $appointment->animal;
            $client = $animal->client;

            // Delete the appointment
            $appointment->delete();

            if ($animal->appointments()->count() === 0) {
                // Delete the animal if it has no appointments
                $animal->delete();

                if ($client->animals()->count() === 0) {
                    // Delete the client if it has no animals
                    $client->delete();
                }
            }
        });

        $request->session()->flash('success', 'Appointment was successfully deleted');

        return to_route('dashboard.appointments.index');
    }
}
