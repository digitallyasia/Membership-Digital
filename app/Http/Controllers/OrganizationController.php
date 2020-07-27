<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class OrganizationController extends Controller
{
    public function edit(Request $request, Organization $organization)
    {
        return Inertia::render('Organizations/Edit', [
            'organization' => $organization,
        ]);
    }

    public function update(Request $request, Organization $organization)
    {
        $validatedData = $request->validate([
            'logo' => request()->hasFile('logo') ? 'required|image|mimes:jpeg,jpg,png' : 'active_url',
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:organizations,email,' . Auth::guard('organization')->user()->id],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ]);
        if ($request->hasFile('logo')) {
            $validatedData['logo'] = Storage::disk('images')->put(
                time() . $request->file('logo')->getClientOriginalExtension(),
                $request->file('logo')
            );
        }
        $organization->update($validatedData);

        return back()->with('success', 'Organization updated.');
    }

    public function toggleAutoJoin(Request $request, Organization $organization)
    {
        $organization->update([
            'auto_join' => !$organization->auto_join
        ]);
        return back()->with('success', 'Auto Join Updated.');
    }
}
