<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Benefit extends JsonResource
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
            // 'organization_id' => $this->organization_id,
            'title' => $this->title,
            'details' => $this->details,
            'image' => $this->image ? \Storage::disk('images')->url(
                $this->image
            ) : null,
            'promocode' => $this->promocode,
        ];
    }
}
