<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;
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
        $organization->update();

        return redirect()->back()->with('success', 'Organization updated.');
    }
}
