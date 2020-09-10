<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

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
            $notificationBuilder = new PayloadNotificationBuilder($notification->organization->name . ': ' . $notification->title);
            $notificationBuilder->setBody($notification->body)
                ->setSound('default');

            $fcm_notification = $notificationBuilder->build();

            $topic = new Topics();
            $topic->topic('organization_' . $notification->organization_id);

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData([
                'organization_id' => $notification->organization_id,
                'type' => 'notification',
                'id' => $notification->id,
            ]);

            $data = $dataBuilder->build();

            $topicResponse = FCM::sendToTopic($topic, null, $fcm_notification, $data);

            $topicResponse->isSuccess();
            $topicResponse->shouldRetry();
            $topicResponse->error();
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
