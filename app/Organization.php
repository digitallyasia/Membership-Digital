<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logo',
        'description',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'user_id',
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
        'user_id' => 'integer',
    ];

    public function benefits()
    {
        return $this->hasMany(\App\Benefit::class);
    }

    public function announcements()
    {
        return $this->hasMany(\App\Announcement::class);
    }

    public function members()
    {
        return $this->belongsToMany(\App\User::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
