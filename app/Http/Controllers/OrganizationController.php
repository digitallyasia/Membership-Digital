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
            'logo' => request()->hasFile('logo') ? 'required|image|mimes:jpeg,jpg,png' : 'required|string|ends_with:.jpg,.jpeg,.png',
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:organizations,email,' . Auth::guard('organization')->user()->id],
            'phone' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'telegram' => ['nullable', 'string', 'max:255'],
        ]);
        if ($request->hasFile('logo')) {
            $logo = time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $validatedData['logo'] = $logo;
            Storage::disk('images')->put(
                $logo,
                file_get_contents($request->file('logo'))
            );
        }
        $organization->update($validatedData);
        return back()->with('success', 'Organization updated.');
    }
    public function toggleAutoJoin(Request $request, Organization $organization)
    {
        if (!$organization->auto_join)
            $organization->pendingMembers()->update([
                'status' => 'accepted'
            ]);
        $organization->update([
            'auto_join' => !$organization->auto_join
        ]);
        return redirect(route('organization.members.active'))->with('success', 'Auto Join Updated.');
    }
    public function leave(Request $request, Organization $organization)
    {
        if ($organization->membership($request->user())) {
            $organization->members()->detach($request->user()->id);
            return response(['message' => 'You have successfully leaved organization']);
        } else {
            return response(['message' => 'You are not member of this organization']);
        }
    }
    public function updateCard(Request $request, Organization $organization)
    {
        $validatedData = $request->validate([
            'card_image' => 'required|image|mimes:jpeg,jpg,png'
        ]);
        if ($request->hasFile('card_image')) {
            $card_image = time() . '.' . $request->file('card_image')->getClientOriginalExtension();
            $validatedData['card_image'] = $card_image;
            Storage::disk('images')->put(
                $card_image,
                file_get_contents($request->file('card_image'))
            );
        }
        $organization->update($validatedData);
        return back()->with('success', 'Card changed successfully.');
    }
}
