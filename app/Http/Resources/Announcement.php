<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Announcement extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'announcement',
            'organization_id' => $this->organization_id,
            'title' => $this->title,
            'details' => $this->details,
            'url' => $this->url,
            'image' => Storage::disk('images')->url(
                $this->image
            ),
            'created_at' => $this->created_at,
        ];
    }
}
