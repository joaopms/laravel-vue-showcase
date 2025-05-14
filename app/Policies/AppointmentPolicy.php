<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return $user->isReceptionist() || ($user->isMedic() && $appointment->medic_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->isReceptionist();
    }

    /**
     * Determine whether the user can assign the appointment to a medic
     */
    public function assign(User $user): bool
    {
        return $user->isReceptionist();
    }

    /**
     * Determine whether the user can **choose** to show all assignments
     */
    public function chooseShowAll(User $user): bool
    {
        return $user->isMedic();
    }
}
