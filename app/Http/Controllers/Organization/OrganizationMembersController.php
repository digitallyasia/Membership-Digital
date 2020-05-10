<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrganizationMembersController extends Controller
{
    public function block(Request $request)
    {
        $request->user()->members()->updateExistingPivot($request->member_id, [
            'status' => 'blocked',
        ]);
        return redirect()->back()->with('success', 'Member successfully blocked.');
    }
    public function unblock(Request $request)
    {
        $request->user()->members()->updateExistingPivot($request->member_id, [
            'status' => 'accepted',
        ]);
        return redirect()->back()->with('success', 'Member successfully unblocked.');
    }
    public function delete(Request $request)
    {
        $request->user()->members()->detach($request->member_id);
        return redirect()->back()->with('success', 'Member successfully deleted.');
    }
}
