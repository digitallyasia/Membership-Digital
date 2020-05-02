<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function create(Request $request)
    {
        return Inertia::render('Notifications/Create');
    }

    public function store(NotificationRequest $request)
    {
        $validated = $request->validated();
        Auth::guard('organization')->user()->notifications()->create($validated);
        return redirect()->route('organization.notifications')->with('success', 'Notification created.');
    }

    public function destroy(Request $request, Notification $notification)
    {
        $notification->delete();

        return redirect()->route('organization.notifications')->with('success', 'Notification deleted.');
    }
}
