<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganizationAccountController extends Controller
{
    public function profile(Request $request)
    {
        return Inertia::render('Organization/Profile', []);
    }

    public function billing(Request $request)
    {
        return Inertia::render('Organization/Billing', [
            'plans' => Plan::all()
        ]);
    }
}
