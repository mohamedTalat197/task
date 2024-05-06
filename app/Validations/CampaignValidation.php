<?php

namespace App\Validations;

use App\Core\AppResult;
use Validator,Auth;

class CampaignValidation
{
    public function validate($payload)
    {
        $input = $payload->all();
        $validationMessages = [
            'brand_id.required'=> __('validationMessage.brand_id_required'),
        ];

        $validator = Validator::make($input , [
            "brand_id" => 'required|exists:brands,id',

        ],$validationMessages);
        if($validator->fails()){
            return AppResult::error($validator->messages()->first());
        }
        return AppResult::success(null);
    }
}
