<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'title',
        'details',
        'url',
        'image',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 25;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'organization_id' => 'integer',
    ];


    public function organization()
    {
        return $this->belongsTo(\App\Organization::class);
    }
}
