<?php

namespace App\Http\Controllers;

use App\Models\InstructorLevel;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function selectRoleView()
    {
        $roles = request()->user()->roles;

        return inertia('Registration/SelectRole', [
            'roles' => $roles,
        ]);
    }

    public function addContactView()
    {
        $contact = request()->user()->contact;

        return inertia('Registration/AddContact', [
            'contact' => $contact,
        ]);
    }

    public function addInstructorLevelView()
    {
        $instructorLevels = InstructorLevel::all();
        $myInstructorLevels =  request()->user()->instructorLevels;

        return inertia('Registration/AddInstructorLevel', [
            'instructorLevels' => $instructorLevels,
            'myInstructorLevels' => $myInstructorLevels,
        ]);
    }

    public function addSchoolView()
    {
        $school = request()->user()->school;

        return inertia('Registration/AddSchool', [
            'school' => $school,
        ]);
    }

    public function addSubjectView()
    {
        $subjects = request()->user()->subjects;

        return inertia('Registration/AddSubject', [
            'subjects' => $subjects,
        ]);
    }

    public function selectRoles(Request $request)
    {
        $roles = [Role::INSTRUCTOR, Role::SCHOOL];

        $validated = Validator::make($request->all(), [
            'roles' => 'required|array|min:1|max:1',
            'roles.*' => 'in:' . implode(',', $roles),
        ], [
            'roles.required' => 'You must select at least one role',
            'roles.min' => 'You must select at least :min role',
            'roles.max' => 'You must select at most :max role',
        ])->validate();

        $request->user()
            ->syncRoles($validated['roles']);

        if ($redirect = $request->query('redirect')) {
            return redirect($redirect);
        }

        return redirect()->route('dashboard');
    }
}
