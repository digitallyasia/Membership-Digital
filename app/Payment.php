<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'plan_id',
        'subscription',
        'bill_id',
        'amount',
        'state',
        'paid',
        'paid_at',
        'original_bill',
        'payment_response',
    ];

    protected $casts = [
        'original_bill' => 'array',
        'payment_response' => 'array',
        'paid' => 'boolean',
        'paid_at' => 'datetime',
        'plan_id' => 'integer',
    ];

    public function organization()
    {
        return $this->belongsTo(\App\Organization::class);
    }

    public function plan()
    {
        return $this->belongsTo(\App\Plan::class);
    }
}
