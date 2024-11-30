<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Team;
use Laravel\Jetstream\Jetstream;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        $adminUser = User::create([
            'name' => 'Reancirl Balaba',
            'email' => 'reancirl@pmna.store',
            'password' => Hash::make('password'),
        ]);

        // Create a Personal Team for Admin User (Jetstream usually expects this)
        $personalTeam = Team::create([
            'name' => $adminUser->name . "'s Team",
            'user_id' => $adminUser->id, // Set the user as the owner
            'personal_team' => true,
        ]);

        // Set the personal team as the current team
        $adminUser->current_team_id = $personalTeam->id;
        $adminUser->save();

        // Create the Admin Team and assign the user as the owner
        $adminTeam = Team::create([
            'name' => 'Admin Team',
            'user_id' => $adminUser->id, // Make the admin user the owner of the team
            'personal_team' => false,
        ]);

        // Assign Admin User to Admin Team
        $adminUser->ownedTeams()->save($adminTeam);
        $adminUser->teams()->attach($adminTeam, ['role' => Jetstream::findRole('admin')->key]);

        // Set the Admin Team as the current team for the admin user (optional)
        $adminUser->current_team_id = $adminTeam->id;
        $adminUser->save();

        // Additional Users to be created
        $users = [
            [
                'name' => 'Jefferson Ebasan',
                'email' => 'jefferson@pmna.store',
            ],
            [
                'name' => 'Sharmaine Bonachita',
                'email' => 'sharmaine@pmna.store',
            ],
            [
                'name' => 'Russel Heyrana',
                'email' => 'russel@pmna.store',
            ],
            [
                'name' => 'Jan Jorille Boyonas',
                'email' => 'jan@pmna.store',
            ],
            [
                'name' => 'Chermae Anobling',
                'email' => 'chermae@pmna.store',
            ],
            [
                'name' => 'Fritz Lapuz',
                'email' => 'fritz@pmna.store',
            ],
            [
                'name' => 'Rexgeo Nacasabog',
                'email' => 'rex@pmna.store',
            ],
        ];

        // Create Additional Users and assign them to the Admin Team + create personal teams
        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
            ]);

            // Create a Personal Team for each user
            $personalTeam = Team::create([
                'name' => $user->name . "'s Team",
                'user_id' => $user->id, // Set the user as the owner
                'personal_team' => true,
            ]);

            // Assign the user to the Admin Team
            $user->teams()->attach($adminTeam, ['role' => Jetstream::findRole('admin')->key]);
        }
    }
}
