<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Payment;
use App\Plan;
use Billplz\Laravel\Billplz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillGenerateController extends Controller
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
        $plan = Plan::findOrFail($request->plan_id);
        $bill1 = Billplz::bill()->create(
            setting('billplz_collection_id'),
            $organization->email,
            $organization->mobile,
            $organization->name,
            $request->subscription === 'monthly' ? $plan->monthly_price . '00' : $plan->yearly_price . '00',
            route('billplz.callback'),
            $request->subscription === 'monthly' ? "Monthly Subscription" : "Yearly Subscription",
            [
                'redirect_url' => route('billplz.redirect')
            ]
        );
        $bill = $bill1->toArray();
        $payment = $organization->payments()->create([
            'plan_id' => $request->plan_id,
            'subscription' => $request->subscription,
            'bill_id' => $bill['id'],
            'amount' => $bill['amount']->getAmount() / 100,
            // 'paid_amount' => $bill['paid_amount']->getAmount(),
            'state' => $bill['state'],
            'paid' => $bill['paid'],
            'paid_at' => $bill['paid_at'],
            'original_bill' => $bill,
        ]);
        return back();
    }
}
