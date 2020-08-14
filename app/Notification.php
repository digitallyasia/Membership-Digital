<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
        static::created(function ($notification) {
            fcm()
                ->toTopic('organization_' . $notification->organization_id)
                ->priority('normal')
                ->timeToLive(0)
                ->notification([
                    'title' => $notification->organization->name . ': ' . $notification->title,
                    'body' => $notification->body,
                ])
                ->data([
                    'organization_id' => $notification->organization_id,
                    'type' => 'notification',
                    'id' => $notification->id,
                ])
                ->send();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'title',
        'body',
    ];

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

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('body', 'like', '%' . $search . '%');
            });
        });
    }
}
