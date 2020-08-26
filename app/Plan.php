<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $casts = [
        'membership_number' => 'boolean',
        'import_membership_csv' => 'boolean',
        'card_customization' => 'boolean',
    ];
}
