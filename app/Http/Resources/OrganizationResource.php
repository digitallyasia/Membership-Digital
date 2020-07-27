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
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'uuid' => $this->uuid,
            'status' => $this->status ?? ($this->pivot ? $this->pivot->status : null),
            'tnc' => $this->tnc,
            'pp' => $this->pp,
            'faq' => $this->faq,
            'announcements' => $this->when(
                !$this->onlyOrganization,
                new AnnouncementCollection(Announcement::where('organization_id', $this->id)->paginate())
            ),
            'benefits' => $this->when(
                !$this->onlyOrganization,
                new BenefitCollection(Benefit::where('organization_id', $this->id)->paginate())
            ),
        ];
    }
}
