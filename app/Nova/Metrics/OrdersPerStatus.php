<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class OrdersPerStatus extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Order::class, 'payment_status')
            ->label(function ($value) {
                switch ($value) {
                    case 0:
                        return 'Payment Pending';
                    case 1:
                        return 'Payment Received';
                    default:
                        return ucfirst($value);
                }
            });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(1);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'orders-per-status';
    }
}
