<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\User;
use App\Models\Contact;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\CountryCitySeeder;
use Database\Seeders\InstructorLevelSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountryCitySeeder::class,
            RoleSeeder::class,
            InstructorLevelSeeder::class,
            SubjectSeeder::class,
            UserSeeder::class,
        ]);
    }
}
