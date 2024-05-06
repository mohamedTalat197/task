<?php

namespace App\Validations;

use App\Core\AppResult;
use Validator,Auth;

class BrandValidation
{
    public function validate($payload)
    {
        $input = $payload->all();
        $validationMessages = [
            'name_ar.required'=> __('validationMessage.name_ar_required'),
            'name_en.required'=> __('validationMessage.name_en_required'),
        ];

        $validator = Validator::make($input , [
            "name_ar" => 'required',
            "name_en" => 'required',
        ],$validationMessages);
        if($validator->fails()){
            return AppResult::error($validator->messages()->first());
        }
        return AppResult::success(null);
    }
}
