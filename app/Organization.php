<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Organization extends Model
{
    use SoftDeletes;

    public static function boot()
    {
        parent::boot();
        static::creating(
            function ($organiztion) {
                $organiztion->uuid = \Str::uuid();
            }
        );
        static::created(
            function ($organiztion) {
                QrCode::format('png')
                    ->size(399)
                    ->color(40, 40, 40)
                    ->generate((string) $organiztion->uuid, './storage/app/public/qrcodes/' . $organiztion->id . '.png');
                $organiztion->update(['qrcode' => $organiztion->uuid . '.png']);
            }
        );
    }

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
        'qrcode',
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
        return $this->belongsToMany(\App\User::class, 'organization_members');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
    public function isMember($user)
    {
        return $this->members()->where('user_id', $user->id)->where('status', true)->exists();
    }
    public function membership($user)
    {
        return $this->members()->where('user_id', $user->id)->withPivot('status')->first();
    }
}
