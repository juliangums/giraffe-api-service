<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->UUID,
            'email' => $this->EMAILADDRESS,
            'name' => $this->FULLNAME,
            'username' => $this->USERNAME,
            'token' => $this->token,
            'phone_number' => $this->PHONENUMBER,
            'profile_image' => $this->profileImage?->WEBURL ?? asset('images/person.png'),
        ];
    }
}
