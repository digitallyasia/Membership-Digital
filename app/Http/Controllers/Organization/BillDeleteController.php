<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Payment;
use App\Plan;
use Billplz\Laravel\Billplz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillDeleteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $organization = Auth::guard('organization')->user();

        $organization->payments()->where('bill_id', $request->bill_id)->delete();
        return back();
    }
}
