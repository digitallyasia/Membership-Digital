<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'logo' => $this->logo,
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'status' => $this->pivot ? $this->pivot->status : null,
            'tnc' => $this->tnc,
            'pp' => $this->pp,
            'faq' => $this->faq,
            // 'qrcode' => $this->qrcode,
            // 'announcements' => new AnnouncementCollection(Announcement::where('organization_id', $this->id)->paginate()),
            // 'benefits' => new BenefitCollection(Benefit::where('organization_id', $this->id)->paginate()),
        ];
    }
}
