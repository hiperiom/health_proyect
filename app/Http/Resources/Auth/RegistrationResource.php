<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
       // dd($this);
        return [
            'id' => $this['user']['id'],
            'email' => $this['user']['email'],
            'first_names' => $this['profile']['first_names'],
            'last_names' => $this['profile']['last_names'],
            // Agrega aquí otros campos relevantes de Auth
        ];
    }
}
