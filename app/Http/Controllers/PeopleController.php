<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InstructorLevel;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = request()->user();
        $isSchool = $user->hasRole(Role::SCHOOL);

        /** @var \Illuminate\Pagination\LengthAwarePaginator */
        $people = User::query()
            ->with('contact')
            ->when(
                $subjectId = request('subject_id'),
                fn ($q) => $q->whereHas('subjects', fn ($subQ) => $subQ->where('id', $subjectId))
            )
            ->when(
                $levelId = request('instructor_level_id'),
                fn ($q) => $q->whereHas('instructorLevels', fn ($lQ) => $lQ->where('id', $levelId))
            )
            ->when(
                $roleId = request('role_id'),
                fn ($q) => $q->role($roleId)
            )
            ->when(
                $country_id = request('country_id') && $isSchool,
                fn ($q) => $q->whereHas('contact', fn ($contactQ) => $contactQ->where('country_id', $country_id))
            )
            ->when(
                $city_id = request('city_id') && $isSchool,
                fn ($q) => $q->whereHas('contact', fn ($contactQ) => $contactQ->where('city_id', $city_id))
            )
            ->when(
                $isSchool,
                fn ($q) => $q->searchWhenExists(request('search'))
            )
            ->paginate(24);

        $people->withQueryString();

        $subjects = Subject::all();

        $roles = Role::where('name', '!=', Role::ADMIN)
            ->get();

        $instructorLevels = InstructorLevel::all();

        return inertia('People/Index', [
            'people' => $people,
            'subjects' => $subjects,
            'roles' => $roles,
            'instructorLevels' => $instructorLevels,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $person)
    {
        if ($person->hasRole('instructor')) {
            $person->load([
                'roles',
                'subjects',
                'instructorLevels',
                'contact',
                'workExperiences'
            ]);
        }
        if ($person->hasRole(Role::SCHOOL)) {
            $person->load([
                'roles',
                'contact',
                'school'
            ]);
        }

        return inertia('People/Show', [
            'person' => $person,
        ]);
    }

    public function showMe()
    {
        return $this->show(request()->user());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
