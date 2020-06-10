<?php

namespace App\Http\Controllers;

use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillPlzCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $key_value = array_combine(array_keys($request->except('x_signature')), array_values($request->except('x_signature')));
        $key_value_pair = array_map(
            function ($v, $k) {
                return sprintf("%s%s", $k, $v);
            },
            $key_value,
            array_keys($key_value)
        );
        sort($key_value_pair);
        if ($request->x_signature === hash_hmac('sha256', implode('|', $key_value_pair), env('BILLPLZ_X_SIGNATURE'))) {
            Payment::where('bill_id', '=', $request->id)->update([
                'amount' => $request->paid_amount,
                'state' => $request->state,
                'paid' => (bool) $request->paid,
                'paid_at' => new \DateTime($request->paid_at),
                'payment_response' => $request->input()
            ]);
            return response('Done', 200);
        }
    }
}