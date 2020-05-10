<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class OrganiztionBenefitsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return Inertia::render('Organization/Benefits', [
            'filters' => Request::all('search', 'trashed'),
            'benefits' => Auth::guard('organization')->user()->benefits()
                ->filter(Request::only('search', 'trashed'))
                ->paginate()
        ]);
    }
}
