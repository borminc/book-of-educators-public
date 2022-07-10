<?php

namespace Database\Seeders;

use App\Models\InstructorLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstructorLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            'Primary' => [
                'Primary School Teacher'
            ],
            'Secondary' => [
                'Secondary School Teacher'
            ],
            'Tertiary' => [
                'Instructor',
                'Lecturer',
                'University Lecturer',
                'Assistance Professor',
                'Associate Professor',
                'Professor',
                'University Professor',
            ]
        ];

        foreach ($levels as $level => $roles) {
            foreach ($roles as $role) {
                InstructorLevel::create([
                    'level' => $level,
                    'role' => $role,
                ]);
            }
        }
    }
}
