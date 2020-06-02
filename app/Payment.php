<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function organization()
    {
        return $this->belongsTo(\App\Organization::class);
    }

    public function plan()
    {
        return $this->belongsTo(\App\Plan::class);
    }
}
