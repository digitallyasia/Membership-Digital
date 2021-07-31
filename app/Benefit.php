<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class Benefit extends Model
{
    use SoftDeletes;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
        static::created(function ($benefit) {
            if ($benefit->send_notification) {
                $notificationBuilder = new PayloadNotificationBuilder($benefit->organization->name . ': ' . $benefit->title);
                $notificationBuilder->setBody($benefit->details)
                    ->setSound('default');

                $notification = $notificationBuilder->build();

                $topic = new Topics();
                $topic->topic('organization_' . $benefit->organization_id);

                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData([
                    'organization_id' => $benefit->organization_id,
                    'type' => 'Rewards',
                    'id' => $benefit->id,
                ]);

                $data = $dataBuilder->build();

                $topicResponse = FCM::sendToTopic($topic, null, $notification, $data);

                $topicResponse->isSuccess();
                $topicResponse->shouldRetry();
                $topicResponse->error();
            }
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
        'redemptions_count',
        'redemption_link',
        'send_notification'
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
        'send_notification' => 'boolean'
    ];


    public function organization()
    {
        return $this->belongsTo(\App\Organization::class);
    }

    public function redemptions()
    {
        return $this->hasMany(\App\Redemption::class);
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
