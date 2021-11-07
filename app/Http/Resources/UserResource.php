<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'address'           => $this->address,
            'user_type'         => $this->user_type,
            'email_verified_at' => $this->email_verified_at,
            'profile_picture'   => $this->profile_picture != null ? asset('uploads/profile/' . $this->profile_picture) : asset('backend/images/avatar.png'),
        ];
    }
}
