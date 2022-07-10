<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env', 'production') === 'production') {
            $subjects = array("Mathematics", "Physics", "Chemistry", "Social Studies", "History", "Science", "Biology", "Calculus", "Linear Algebra", "Software Engineering", "Computer Architecture", "Artificial Intelligence", "IT Project Management", "Microeconomics", "Academic English");
            foreach ($subjects as $s) {
                Subject::create([
                    'name' => $s,
                ]);
            }
            return;
        }

        Subject::factory(25)
            ->create();
    }
}
