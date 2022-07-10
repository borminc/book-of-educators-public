<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolUserController extends Controller
{
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'user_role_in_school' => ['required', 'string', 'max:255'],
        ])->validate();

        $user = $request->user();

        $user->school()->updateOrCreate(['user_id' => $user->id], $validated);

        if ($redirect = $request->query('redirect')) {
            return redirect($redirect);
        }

        return redirect()->route('dashboard');
    }

    public function edit(Request $request)
    {
        $user = $request->user();

        return inertia('School/Edit', [
            'school' => $user->school
        ]);
    }

    public function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'user_role_in_school' => ['required', 'string', 'max:255'],
        ], [
            'name' => 'school name',
            'user_role_in_school' => 'role in school',
        ])->validate();

        $request->user()->school()->update($validated);

        if ($redirect = $request->query('redirect')) {
            return redirect($redirect);
        }

        return back();
    }
}
