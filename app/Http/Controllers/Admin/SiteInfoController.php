<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NumberHelper;
use App\Helpers\SmsHelper;
use App\Http\Collections\ServiceCollection;
use App\Http\Collections\SliderCollection;
use App\Http\Resources\SiteTextResource;
use App\Repos\ServiceRepo;
use App\Repos\SliderRepo;
use App\Repos\UserAddRepo;
use App\Repos\SiteInfoRepo;
use App\Validations\UserValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Validator,Auth,Artisan,Hash,File,Crypt;
use App\Http\Resources\UserResource;
use App\Models\User;

class SiteInfoController extends Controller
{
    use \App\Traits\ApiResponseTrait;

    use \App\Traits\ApiResponseTrait;

    private $siteInfoRepo;

    public function __construct(SiteInfoRepo $siteInfoRepo)
    {
        $this->siteInfoRepo=$siteInfoRepo;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get(){
        return $this->apiResponseData(new SiteTextResource($this->siteInfoRepo->get()));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request){
        $info=$this->siteInfoRepo->update($request);
        return $this->apiResponseData(new SiteTextResource($info));
    }

}
