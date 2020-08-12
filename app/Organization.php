<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Organization extends Authenticatable
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
                    ->margin(1)
                    ->generate((string) $organiztion->uuid, storage_path('app/public/qrcodes/' . $organiztion->uuid . '.png'));
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
        'address',
        'auto_join',
        'city',
        'description',
        'email_verified_at',
        'email',
        'faq',
        'logo',
        'name',
        'password',
        'phone',
        'plan_id',
        'postal_code',
        'pp',
        'qrcode',
        'state',
        'subscription_expire_at',
        'tnc',
        'uuid',
        'website',
        'facebook',
        'whatsapp',
        'instagram',
        'youtube',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'subscription_expire_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'auto_join' => 'boolean'
    ];

    public function benefits()
    {
        return $this->hasMany(\App\Benefit::class);
    }

    public function benefitsWithTrashed()
    {
        return $this->benefits()->withTrashed();
    }

    public function announcements()
    {
        return $this->hasMany(\App\Announcement::class);
    }

    public function announcementsWithTrashed()
    {
        return $this->announcements()->withTrashed();
    }

    public function notifications()
    {
        return $this->hasMany(\App\Notification::class);
    }
    public function payments()
    {
        return $this->hasMany(\App\Payment::class);
    }
    public function pendingPayment()
    {
        return $this->payments()->where('state', 'due');
    }

    public function notificationsWithTrashed()
    {
        return $this->notifications()->withTrashed();
    }

    public function members()
    {
        return $this->belongsToMany(\App\User::class, 'organization_members')->withPivot('status')->withTimestamps();
    }
    public function activeMembers()
    {
        return $this->belongsToMany(\App\User::class, 'organization_members')->wherePivot('status', 'accepted');
    }
    public function pendingMembers()
    {
        return $this->belongsToMany(\App\User::class, 'organization_members')->wherePivot('status', 'pending');
    }
    public function blockedMembers()
    {
        return $this->belongsToMany(\App\User::class, 'organization_members')->wherePivot('status', 'blocked');
    }

    public function isMember($user)
    {
        return $this->members()->where('user_id', $user->id)->where('status', 'accepted')->exists();
    }

    public function membership($user)
    {
        return $this->members()->where('user_id', $user->id)->withPivot('status')->first();
    }
    public function subscription()
    {
        return $this->belongsTo(\App\Plan::class, 'plan_id');
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('name');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        });
    }
}
