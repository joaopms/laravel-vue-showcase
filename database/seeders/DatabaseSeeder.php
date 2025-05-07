<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\UserType;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()
            ->count(10)
            ->state(new Sequence(
                ['type' => UserType::Receptionist],
                ['type' => UserType::Medic],
            ))
            ->create();
        $receptionists = $users->where('type', UserType::Receptionist);
        $medics = $users->where('type', UserType::Medic);

        $clients = Client::factory()
            ->count(20)
            ->create();

        $animals = Animal::factory()
            ->count(30)
            ->recycle($clients)
            ->create();

        // "Just in" appointments
        Appointment::factory()
            ->count(20)
            ->recycle($animals)
            ->create();

        // Assigned attachments
        Appointment::factory()
            ->count(40)
            ->assigned()
            ->recycle([$animals, $users])
            ->state(new Sequence(fn () => [
                'receptionist_id' => $receptionists->random()->id,
                'medic_id' => $medics->random()->id,
            ]))
            ->create();
    }
}
