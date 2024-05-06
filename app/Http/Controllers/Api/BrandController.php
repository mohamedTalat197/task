<?php

namespace App\Http\Controllers\Api;

use App\Core\AppResult;
use App\Http\Collections\BrandCollection;
use App\Http\Resources\BrandResource;
use App\Repos\BrandRepo;
use App\Validations\BrandValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Validator, Auth, Artisan, Hash, File, Crypt;

class BrandController extends Controller
{
    use \App\Traits\ApiResponseTrait;
    private $brandRepo;
    private $brandValidation;



    public function __construct(BrandRepo $brandRepo, BrandValidation $brandValidation)
    {
        $this->brandRepo=$brandRepo;
        $this->brandValidation=$brandValidation;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $request['user_id'] =Auth::user()->id;
        $brands = $this->brandRepo->get($request);
        return $this->apiResponseData(new BrandCollection($brands));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function single(Request $request)
    {
        $response = $this->brandRepo->getBrandById($request->brand_id);
        return $this->apiResponseData(new BrandResource($response->data));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        App::setLocale($request->header('lang'));
        $validateBrand = $this->brandValidation->validate($request);
        if($validateBrand->operationType==ERROR)
            return $this->apiResponseMessage(0,$validateBrand->error,200);
        $request['user_id']=Auth::user()->id;
        $brand = $this->brandRepo->create($request);
        return $this->apiResponseData(new BrandResource($brand));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        App::setLocale($request->header('lang'));
        $response = $this->brandRepo->getBrandById($request->brand_id);
        $brand=$response->data;
        $validateBrand = $this->brandValidation->validate($request);
        if($validateBrand->operationType==ERROR)
            return $this->apiResponseMessage(0,$validateBrand->error,200);
        $data = $this->brandRepo->update($request,$brand);
        return $this->apiResponseData(new BrandResource($data));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        App::setLocale($request->header('lang'));
        $response = $this->brandRepo->getBrandById($request->brand_id);
        $this->brandRepo->delete($response->data);
        return $this->apiResponseMessage(1,'deleted successfully');
    }

}
