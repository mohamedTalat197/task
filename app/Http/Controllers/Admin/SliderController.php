<?php

namespace App\Http\Controllers\Admin;

use App\Core\AppResult;
use App\Http\Collections\SliderCollection;
use App\Http\Resources\SliderResource;
use App\Repos\SliderRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Validator, Auth, Artisan, Hash, File, Crypt;

class SliderController extends Controller
{
    use \App\Traits\ApiResponseTrait;
    private $sliderRepo;

    public function __construct(SliderRepo $sliderRepo)
    {
        $this->sliderRepo=$sliderRepo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get(Request $request){
        $cities=$this->sliderRepo->get($request);
        return $this->apiResponseData(new SliderCollection($cities));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function single(Request $request){
        $response=$this->sliderRepo->getSliderById($request->slider_id);
        return $this->apiResponseData(new SliderResource($response->data));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request){
        App::setLocale($request->header('lang'));
        $validateSlider=$this->validateSlider($request);
        if($validateSlider->operationType==ERROR)
            return $this->apiResponseMessage(0,$validateSlider->error,200);
        $service=$this->sliderRepo->create($request);
        return $this->apiResponseData(new SliderResource($service));
    }

    /***
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request){
        $response=$this->sliderRepo->getSliderById($request->slider_id);
        $service=$response->data;
        App::setLocale($request->header('lang'));
        $validateSlider=$this->validateSlider($request);
        if($validateSlider->operationType==ERROR)
            return $this->apiResponseMessage(0,$validateSlider->error,200);
        $service=$this->sliderRepo->update($request,$service);
        return $this->apiResponseData(new SliderResource($service));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request){
        $response=$this->sliderRepo->getSliderById($request->slider_id);
        $this->sliderRepo->delete($response->data);
        return $this->apiResponseMessage(1,'deleted successfully');
    }

    private function validateSlider($payload){
        $input = $payload->all();
        $validationMessages = [
            'title_ar.required' => __('validationMessage.title_ar_required'),
            'title_en.required' => __('validationMessage.title_en_required'),
        ];

        $validator = Validator::make($input, [
            'title_ar' => 'required' ,
            'title_en' => 'required' ,
        ],$validationMessages);
        if ($validator->fails()) {
            return AppResult::error($validator->messages()->first());
        }
        return AppResult::success(null);
    }

}
