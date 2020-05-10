<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class OrganiztionMembersController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Organization/Members', [
            'filters' => Request::all('search', 'trashed'),
            'members' => Auth::guard('organization')->user()->activeMembers()
                ->paginate()
        ]);
    }
}
