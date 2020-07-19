<?php

namespace App;

use App\Traits\Imageable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Benefit extends Model
{
    use SoftDeletes, Imageable;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
        static::created(function ($benefit) {
            fcm()
                ->toTopic('organization_' . $benefit->organization_id)
                ->priority('normal')
                ->timeToLive(0)
                ->notification([
                    'title' => $benefit->title,
                    'body' => $benefit->details,
                ])
                ->data([
                    'organization_id' => $benefit->organization_id,
                    'type' => 'Benefit',
                    'id' => $benefit->id,
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
        'details',
        'image',
        'promo_code',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

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
                    ->orWhere('details', 'like', '%' . $search . '%')
                    ->orWhere('promo_code', 'like', '%' . $search . '%');
            });
        });
    }
}
