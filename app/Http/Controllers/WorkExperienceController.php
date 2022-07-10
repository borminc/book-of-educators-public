<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\Validator;

class WorkExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = request()->user();

        $user->load('school');

        $workExperiences = $user->workExperiences()
            ->orderBy('start_date', 'desc')
            ->get();

        $instructorLevels = $user->instructorLevels()
            ->get();

        $subjects = $user->subjects()->get();

        return inertia('WorkExperiences/Index', [
            'user' => $user,
            'instructorLevels' => $instructorLevels,
            'workExperiences' => $workExperiences,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!request()->user()->hasRole('instructor'), 403);

        return inertia('WorkExperiences/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!request()->user()->hasRole('instructor'), 403);

        $validated = Validator::make($request->all(), [
            'company' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ])->validate();

        $request->user()
            ->workExperiences()
            ->create($validated);

        return redirect()->route('work-experiences.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkExperience  $workExperience
     * @return \Illuminate\Http\Response
     */
    public function show(WorkExperience $workExperience)
    {
        return inertia('WorkExperiences/Show', [
            'workExperience' => $workExperience,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkExperience  $workExperience
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkExperience $workExperience)
    {
        abort_if(request()->user()->id !== $workExperience->user_id, 403);

        return inertia('WorkExperiences/Edit', [
            'workExperience' => $workExperience,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkExperience  $workExperience
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkExperience $workExperience)
    {
        abort_if(request()->user()->id !== $workExperience->user_id, 403);

        $validated = Validator::make($request->all(), [
            'company' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ])->validate();

        $workExperience->update($validated);

        return redirect()->route('work-experiences.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkExperience  $workExperience
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkExperience $workExperience)
    {
        abort_if(request()->user()->id !== $workExperience->user_id, 403);

        $workExperience->delete();
        return redirect()->route('work-experiences.index');
    }
}
