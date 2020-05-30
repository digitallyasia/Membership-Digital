<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait Imageable
{
    public function getImageAttribute($value)
    {
        return $value !== null
            ? preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $value)
            ? $value
            : Storage::disk('images')->url($value)
            : null;
    }
}
