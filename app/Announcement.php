<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class Announcement extends Model
{
    use SoftDeletes;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
        static::created(function ($announcement) {
            $notificationBuilder = new PayloadNotificationBuilder($announcement->organization->name . ': ' . $announcement->title);
            $notificationBuilder->setBody($announcement->details)
                ->setSound('default');

            $notification = $notificationBuilder->build();

            $topic = new Topics();
            $topic->topic('organization_' . $announcement->organization_id);

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData([
                'organization_id' => $announcement->organization_id,
                'type' => 'Announcements',
                'id' => $announcement->id,
            ]);

            $data = $dataBuilder->build();

            $topicResponse = FCM::sendToTopic($topic, null, $notification, $data);

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
        'details',
        'url',
        'image',
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
                    ->orWhere('details', 'like', '%' . $search . '%');
            });
        });
    }
}
