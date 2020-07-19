<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait Imageable
{
    public function getImageAttribute($value)
    {
        return $value !== null
            ? (preg_match('|^http(s)?://|', $value)
                ? $value
                : Storage::disk('images')->url($value))
            : null;
    }
}
