<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstructorLevel;
use Illuminate\Support\Facades\Validator;

class UserInstructorLevelController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'instructor_level_ids' => 'required|array|min:1',
            'instructor_level_ids.*' => 'required|integer|exists:instructor_levels,id',
        ], [
            'instructor_level_ids.required' => 'You must select at least one level.',
            'instructor_level_ids.*.required' => 'You must select at least one level.'
        ])->validated();

        $request->user()
            ->instructorLevels()
            ->sync($validated['instructor_level_ids']);

        return redirect()->route('dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InstructorLevel  $instructorLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $instructorLevels = $request->user()->instructorLevels;

        return inertia('InstructorLevels/Edit', [
            'allInstructorLevels' => InstructorLevel::all(),
            'myInstructorLevels' => $instructorLevels,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InstructorLevel  $instructorLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'instructor_level_ids' => 'required|array|min:1',
            'instructor_level_ids.*' => 'required|integer|exists:instructor_levels,id',
        ], [
            'instructor_level_ids.required' => 'You must select at least one level.',
            'instructor_level_ids.*.required' => 'You must select at least one level.'
        ])->validated();

        $request->user()
            ->instructorLevels()
            ->sync($validated['instructor_level_ids']);

        if ($redirect = $request->query('redirect')) {
            return redirect($redirect);
        }

        return redirect()->route('dashboard');
    }
}
