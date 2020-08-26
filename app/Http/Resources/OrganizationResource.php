<?php

namespace App\Http\Resources;

use App\Announcement;
use App\Benefit;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrganizationResource extends JsonResource
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
            'name' => $this->name,
            'logo' => $this->logo ? Storage::disk('images')->url(
                $this->logo
            ) : null,
            'card_image' => $this->card_image ? Storage::disk('images')->url(
                $this->card_image
            ) : null,
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'uuid' => $this->uuid,
            'status' => $this->status ?? ($this->pivot ? $this->pivot->status : null),
            'joined_at' => $this->pivot ? $this->pivot->created_at : null,
            'membership_id' => $this->pivot ? ($this->pivot->membership_id ? strtoupper(substr($this->name, 0, 3) . sprintf("%'06d", $this->pivot->membership_id)) : null) : null,
            'tnc' => $this->tnc,
            'pp' => $this->pp,
            'faq' => $this->faq,
            'website' => $this->website,
            'facebook' => $this->facebook,
            'whatsapp' => $this->whatsapp,
            'instagram' => $this->instagram,
            'youtube' => $this->youtube,
            'twitter' => $this->twitter,
            'telegram' => $this->telegram,

        ];
    }
}
