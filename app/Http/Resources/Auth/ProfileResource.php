<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
       //  dd($this);
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_names' => $this->profile->first_names,
            'last_names' => $this->profile->last_names,
            'gender' => $this->profile->gender,
            'birthday' => $this->profile->getRawOriginal('birthday'),
            'dni' => $this->dni,
            'profile_photo_url' => $this->profile_photo_url,
        ];
    }
}
