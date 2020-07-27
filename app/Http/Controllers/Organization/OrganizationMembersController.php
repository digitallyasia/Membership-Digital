<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class OrganizationMembersController extends Controller
{
    public function activeMembers(Request $request)
    {
        return Inertia::render('Organization/Members', [
            'filters' => Request::all('search', 'trashed'),
            'tab' => 'Accepted',
            'members' => Auth::guard('organization')->user()->activeMembers()
                ->paginate()
        ]);
    }

    public function pendingMembers(Request $request)
    {
        return Inertia::render('Organization/Members', [
            'filters' => Request::all('search', 'trashed'),
            'tab' => 'Pending',
            'members' => Auth::guard('organization')->user()->pendingMembers()
                ->paginate()
        ]);
    }

    public function blockedMembers(Request $request)
    {
        return Inertia::render('Organization/Members', [
            'filters' => Request::all('search', 'trashed'),
            'tab' => 'Blocked',
            'members' => Auth::guard('organization')->user()->blockedMembers()
                ->paginate()
        ]);
    }

    public function block(Request $request)
    {
        Auth::guard('organization')->user()->members()->updateExistingPivot(Request::input('member_id'), [
            'status' => 'blocked',
        ]);
        return back()->with('success', 'Member successfully blocked.');
    }

    public function unblock(Request $request)
    {
        Auth::guard('organization')->user()->members()->updateExistingPivot(Request::input('member_id'), [
            'status' => 'accepted',
        ]);
        return back()->with('success', 'Member successfully unblocked.');
    }

    public function delete(Request $request)
    {
        Auth::guard('organization')->user()->members()->detach(Request::input('member_id'));
        return back()->with('success', 'Member successfully deleted.');
    }

    public function acceptJoinRequest(Request $request)
    {
        Auth::guard('organization')->user()->members()->updateExistingPivot(Request::input('member_id'), [
            'status' => 'accepted',
        ]);
        return back()->with('success', 'Join Request Accepted.');
    }
}
