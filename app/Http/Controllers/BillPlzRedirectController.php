<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillPlzRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $billplz = $request->billplz;
        $key_value = $billplz;
        unset($key_value['x_signature']);
        $key_value_pair = array_map(
            function ($v, $k) {
                return sprintf("billplz%s%s", $k, $v);
            },
            $key_value,
            array_keys($key_value)
        );
        sort($key_value_pair);
        if ($billplz['x_signature'] === hash_hmac('sha256', implode('|', $key_value_pair), env('BILLPLZ_X_SIGNATURE'))) {
            $payment = Payment::where('bill_id', '=', $billplz['id'])->first();
            $payment->update([
                'paid' => (bool) $billplz['paid'],
                'paid_at' => new \DateTime($billplz['paid_at']),
            ]);
            Organization::where('id', '=', $payment->organization_id)->update([
                'plan_id' => $payment->plan_id,
                'subscription_expire_at' => $payment->subscription === 'monthly' ? Carbon::now()->addMonth() : Carbon::now()->addYear()
            ]);
            return redirect(route('organization.billing'));
        }
        return redirect(route('organization.dashboard'));
    }
}
