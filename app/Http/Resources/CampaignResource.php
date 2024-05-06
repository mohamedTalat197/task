<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'desc' => $request->header('lang') == 'en' ? $this->desc_en : $this->desc_ar,
            'video' => ImageHelper::getInstance()->getImageUrl('Campaign',$this->video),
            'type'  =>  (int)$this->campaignType ,
            'name_ar' =>  $this->name_ar ,
            'name_en' =>  $this->name_en ,
            'desc_ar' =>  $this->desc_ar ,
            'desc_en' =>  $this->desc_en ,
            'brand'=>new BrandResource($this->brand),
        ];
    }
}
