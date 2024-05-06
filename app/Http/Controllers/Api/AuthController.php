<?php

namespace App\Http\Controllers\Api;

use App\Helpers\NumberHelper;
use App\Helpers\SmsHelper;
use App\Repos\UserRepo;
use App\Validations\UserValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Validator,Auth,Artisan,Hash,File,Crypt;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    use \App\Traits\ApiResponseTrait;
    private $userRepo;
    private $userValidation;

    public function __construct(UserRepo $userRepo,UserValidation $userValidation)
    {
        $this->userRepo=$userRepo;
        $this->userValidation=$userValidation;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        App::setLocale($request->header('lang'));
        $response = $this->userRepo->getUserByPhone($request);
        if ($response->operationType == ERROR)
            return $this->apiResponseMessage(0, $response->error);
        $user = $response->data;
        $password = Hash::check($request->password, $user->password);
        if ($password == false)
            return $this->apiResponseMessage(0, __('responseMessage.invalid_password'), 200);
        $this->putTokenInUser($user);
        return $this->apiResponseData(new UserResource($user));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function my_info()
    {
        $user = Auth::user();
        return $this->apiResponseData(new UserResource($user));
    }

    /**
     * @param $user
     * @return mixed
     */
    private function putTokenInUser($user){
        return  $user['user_token'] = $user->createToken('TutsForWeb')->accessToken;
    }

}
