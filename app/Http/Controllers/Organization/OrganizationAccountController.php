<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Payment;
use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'plans' => Plan::all(),
            'payments' => Auth::guard('organization')->user()->payments()->with('plan')->paginate(),
            'pendingPayment' => Auth::guard('organization')->user()->pendingPayment()->first(),
        ]);
    }
}
