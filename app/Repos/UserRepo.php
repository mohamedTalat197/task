<?php

namespace App\Repos;

use App\Core\AppResult;
use App\Helpers\ImageHelper;
use App\Helpers\NumberHelper;
use App\Models\User;

use Validator, Auth, Artisan, Hash, File, Crypt;

class UserRepo
{

    /**
     * @param $filter
     * @return mixed
     */
    public function get($filter)
    {
        $users = User::orderBy('id', 'desc');
        if($filter->parent_id)
            $users=$users->where('parent_id',$filter->parent_id);
        if($filter->type)
            $users=$users->where('type',$filter->type);
        if($filter->search_text) {
            $users = $users->where(function($q)use($filter){
                $q->where('phone', 'LIKE', '%' . $filter->search_text . '%')
                    ->orWhere('name', 'LIKE', '%' . $filter->search_text . '%')
                    ->orWhere('email', 'LIKE', '%' . $filter->search_text . '%');
            });
        }
        $users = $users->paginate(10);
        return $users;
    }

    /**
     * @param $payload
     * @param $type
     * @return User
     */
    public function create($payload)
    {
        $user = new User();
        $user->name = $payload->name;
        $user->payment_status = $payload->package_id ==1 ? 1 : 0;
        $user->email = $payload->email;
        $user->phone = $payload->phone;
        $user->password = Hash::make($payload->password);
        $user->realPassword = $payload->password;
        $user->type = $payload->type;
        $user->status = $payload->status;
        $user->firebase = $payload->firebase;
        $user->lang = $payload->header('lang');
        $user->country_id = $payload->country_id;
        $user->parent_id = $payload->parent_id;
        $user->note = $payload->note;
        $user->msgCode = NumberHelper::getInstance()->generateCode();
        $user->save();
        return $user;
    }

    /**
     * @param $payload
     * @param $user
     * @return mixed
     */
    public function update($payload,$user)
    {
        if(isset($payload->name))
            $user->name = $payload->name;
        if(isset($payload->email))
            $user->email = $payload->email;
        if(isset($payload->phone))
            $user->phone = $payload->phone;
        if($payload->password) {
            $user->password = Hash::make($payload->password);
            $user->realPassword = $payload->password;
        }
        if(isset($payload->note))
            $user->note = $payload->note;
        $user->save();
        return $user;
    }


    /**
     * @param $id
     * @return AppResult
     */
    public function getUserById($id)
    {
        $user = User::find($id);
        return AppResult::success($user);
    }


    /**
     * @param $payload
     * @return AppResult
     */
    public function getUserByPhone($payload)
    {
        $user = User::where('phone', $payload->phone)->first();
        if (is_null($user))
            return AppResult::error(__('responseMessage.user_not_found'));
        return AppResult::success($user);
    }

    /**
     * @param $email
     * @return AppResult
     */
    public function getUserByEmail($email)
    {
        $user = User::where('email', $email)->first();
        if (is_null($user))
            return AppResult::error(__('responseMessage.user_not_found'));
        return AppResult::success($user);

    }

    /**
     * @param $user
     * @param $code
     * @return AppResult
     */
    public function checkCode($user, $code)
    {
        if ($user->msgCode == $code) {
            $user->msgCode = null;
            $user->save();
            $this->change_status($user, 1);
            return AppResult::success('Code verified successfully');

        } else {
            return AppResult::error(__('validationMessage.incorrect_code'));
        }
    }


    /**
     * @param $user
     */
    public function generateCodeToUser($user)
    {
        $user->msgCode = NumberHelper::getInstance()->generateCode();
        $user->save();
    }





    /**
     * @param $user
     * @param $payload
     */
    public function change_password($user, $payload)
    {
        $user->password = Hash::make($payload->password);
        $user->realPassword = $payload->password;
        $user->save();
    }


    /**
     * @param $user
     * @param $status
     * @return void
     */
    public function change_status($user, $status)
    {
        $user->status = $status;
        $user->save();
    }


    /**
     * @param $user
     */
    public function complete_account_data($user)
    {
        $user->isCompleteAccountInfo = 1;
        $user->save();
    }

    /**
     * @param $user
     * @param $payload
     */
    public function save_firebase($user, $payload)
    {
        $user->firebase = $payload->firebase;
        $user->save();
    }





}
