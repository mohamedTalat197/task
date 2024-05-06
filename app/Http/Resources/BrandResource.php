<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'name' => $request->header('lang') == 'en' ? $this->name_en : $this->name_ar,
            'image' => ImageHelper::getInstance()->getImageUrl('Brand',$this->image),
            'name_ar' =>  $this->name_ar ,
            'name_en' =>  $this->name_en ,
            'user'=>new UserResource($this->user),
        ];
    }
}
