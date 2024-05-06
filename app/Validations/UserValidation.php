<?php

namespace App\Validations;

use App\Core\AppResult;
use Validator,Auth;

class UserValidation
{
    public function validate($payload,$user_id)
    {
        $input = $payload->all();
        $validationMessages = [
            'phone.required'=> __('validationMessage.phone_required'),
            'phone.unique'=> __('validationMessage.phone_unique'),
            'email.required'=> __('validationMessage.email_required'),
            'email.unique'=> __('validationMessage.email_unique'),
        ];

        $validateArray=$this->validateUser($user_id,$payload);
        $validator = Validator::make($input,$validateArray,$validationMessages);
        if($validator->fails()){
            return AppResult::error($validator->messages()->first());
        }
        return AppResult::success(null);
    }

    /**
     * @param $user_id
     * @param $payload
     * @return string[]
     */
    private function validateUser($user_id,$payload){
        return [
            'phone' => $user_id == 0 ? 'required|unique:users' : 'required|unique:users,phone,' . $user_id ,
            'email' => $user_id == 0 ? 'required|unique:users|regex:/(.+)@(.+)\.(.+)/i' : 'required|unique:users,email,' . $user_id . '|regex:/(.+)@(.+)\.(.+)/i',
            'password' => $user_id != 0 ? '' : 'required',
        ];
    }






}
