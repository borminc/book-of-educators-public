<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
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
            'phone' => 'required|string|max:100',
            'email' => 'required|email',
            'address_1' => 'nullable|string|max:100',
            'address_2' => 'nullable|string|max:100',
            'city_id' => 'required|integer|exists:cities,id',
            'country_id' => 'required|integer|exists:countries,id',
        ], [
            'city_id.required' => 'The city field is required.',
            'country_id.required' => 'The country field is required.',
        ])->validated();

        $user = $request->user();
        $user->contact()
            ->updateOrCreate(['user_id' => $user->id], $validated);

        return redirect()->route('dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $contact = $request->user()->contact;

        return inertia('Contacts/Edit', [
            'contact' => $contact,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $contact = $request->user()->contact;

        $validated = Validator::make($request->all(), [
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'address_1' => 'nullable|string|max:100',
            'address_2' => 'nullable|string|max:100',
            'city_id' => 'nullable|integer|exists:cities,id',
            'country_id' => 'nullable|integer|exists:countries,id',
        ])->validated();

        $contact->update($validated);

        return back()->with('message', 'Saved');
    }
}
