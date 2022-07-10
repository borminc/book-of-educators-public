<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubjectUserController extends Controller
{
    public function edit(Request $request)
    {
        $subjects = $request->user()->subjects;

        return inertia('SubjectsUser/Edit', [
            'subjects' => $subjects
        ]);
    }

    public function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'required|integer|exists:subjects,id',
        ], [
            'subject_ids.required' => 'At least one subject is required.'
        ])->validate();

        $request->user()
            ->subjects()
            ->sync($validated['subject_ids']);

        if ($redirect = $request->query('redirect')) {
            return redirect($redirect);
        }

        return redirect()->route('dashboard');
    }
}
