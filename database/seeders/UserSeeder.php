<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Role;
use App\Models\User;
use App\Models\Contact;
use App\Models\Subject;
use App\Models\WorkExperience;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env', 'production') === 'production') {
            return;
        }

        $testUser = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'test@test.com',
        ]);

        $users = User::factory(100)->create();

        $users->push($testUser);


        $subjects = Subject::all();
        $roles = collect([Role::INSTRUCTOR, Role::SCHOOL]);

        $users->each(function ($user) use ($subjects, $roles) {
            $assignedRole = $roles->random();
            $user->assignRole($assignedRole);

            if ($assignedRole === Role::SCHOOL) {
                School::factory()->for($user)->create();
            } else {
                $user->subjects()
                    ->attach($subjects->random(2));

                WorkExperience::factory(random_int(3, 5))
                    ->for($user)
                    ->create();
            }

            Contact::factory()
                ->for($user)
                ->create();
        });
    }
}
