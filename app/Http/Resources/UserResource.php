<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'name' => $this->name,
            'email' => $this->email,
            'status' => (int)$this->status,
            'active_code' => (int)$this->active_code,
            'password_code' => (int)$this->password_code,
            'lang'=>$this->lang,
            'firebase'=>$this->firebase,
            'token' => $this->user_token,
        ];
    }
}
