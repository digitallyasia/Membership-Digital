<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'profile_picture' => $this->profile_picture ? Storage::disk('images')->url(
                $this->profile_picture
            ) : null,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone_number' => $this->phone_number,
            'user_name' => $this->user_name,
            'fcm_token' => $this->fcm_token,
            'address' => $this->address,
            'date_of_birth' => $this->date_of_birth,
            'token' => $this->when($this->token !== null, $this->token),
            // 'organizations' => Organization::collection($this->organizations),
        ];
    }
}
