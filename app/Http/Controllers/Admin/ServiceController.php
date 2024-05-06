<?php

namespace App\Http\Controllers\Admin;

use App\Core\AppResult;
use App\Http\Collections\ServiceCollection;
use App\Http\Resources\ServiceResource;
use App\Repos\ServiceRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Validator, Auth, Artisan, Hash, File, Crypt;

class ServiceController extends Controller
{
    use \App\Traits\ApiResponseTrait;
    private $serviceRepo;

    public function __construct(ServiceRepo $serviceRepo)
    {
        $this->serviceRepo=$serviceRepo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get(Request $request){
        $cities=$this->serviceRepo->get($request);
        return $this->apiResponseData(new ServiceCollection($cities));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function single(Request $request){
        $response=$this->serviceRepo->getServiceById($request->service_id);
        return $this->apiResponseData(new ServiceResource($response->data));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request){
        App::setLocale($request->header('lang'));
        $validateService=$this->validateService($request);
        if($validateService->operationType==ERROR)
            return $this->apiResponseMessage(0,$validateService->error,200);
        $service=$this->serviceRepo->create($request);
        return $this->apiResponseData(new ServiceResource($service));
    }

    /***
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request){
        $response=$this->serviceRepo->getServiceById($request->service_id);
        $service=$response->data;
        App::setLocale($request->header('lang'));
        $validateService=$this->validateService($request);
        if($validateService->operationType==ERROR)
            return $this->apiResponseMessage(0,$validateService->error,200);
        $service=$this->serviceRepo->update($request,$service);
        return $this->apiResponseData(new ServiceResource($service));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request){
        $response=$this->serviceRepo->getServiceById($request->service_id);
        $this->serviceRepo->delete($response->data);
        return $this->apiResponseMessage(1,'deleted successfully');
    }

    private function validateService($payload){
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
