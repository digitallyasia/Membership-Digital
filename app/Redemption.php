<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    protected $fillable = ['user_id', 'benefit_id'];

    public function benefit()
    {
        return $this->belongsTo(\App\Benefit::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
