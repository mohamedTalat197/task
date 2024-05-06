<?php

namespace App\Http\Controllers\Admin;

use App\Core\AppResult;
use App\Http\Collections\PortfolioCollection;
use App\Http\Resources\PortfolioResource;
use App\Repos\PortfolioImageRepo;
use App\Repos\PortfolioRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Validator, Auth, Artisan, Hash, File, Crypt;

class PortfolioController extends Controller
{
    use \App\Traits\ApiResponseTrait;
    private $portfolioRepo;
    private $portfolioImageRepo;

    public function __construct(PortfolioRepo $portfolioRepo,PortfolioImageRepo $portfolioImageRepo)
    {
        $this->portfolioRepo=$portfolioRepo;
        $this->portfolioImageRepo=$portfolioImageRepo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get(Request $request){
        $cities=$this->portfolioRepo->get($request);
        return $this->apiResponseData(new PortfolioCollection($cities));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function single(Request $request){
        $response=$this->portfolioRepo->getPortfolioById($request->portfolio_id);
        return $this->apiResponseData(new PortfolioResource($response->data));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request){
        App::setLocale($request->header('lang'));
        $validatePortfolio=$this->validatePortfolio($request);
        if($validatePortfolio->operationType==ERROR)
            return $this->apiResponseMessage(0,$validatePortfolio->error,200);
        $data=$this->portfolioRepo->create($request);
        if(isset($request->images))
            $this->portfolioImageRepo->create($request->images,$data->id);
        return $this->apiResponseData(new PortfolioResource($data));
    }

    /***
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request){
        $response=$this->portfolioRepo->getPortfolioById($request->portfolio_id);
        $service=$response->data;
        App::setLocale($request->header('lang'));
        $validatePortfolio=$this->validatePortfolio($request);
        if($validatePortfolio->operationType==ERROR)
            return $this->apiResponseMessage(0,$validatePortfolio->error,200);
        $data=$this->portfolioRepo->update($request,$service);
        if(isset($request->images))
            $this->portfolioImageRepo->create($request->images,$data->id);
        return $this->apiResponseData(new PortfolioResource($data));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request){
        $response=$this->portfolioRepo->getPortfolioById($request->portfolio_id);
        $this->portfolioRepo->delete($response->data);
        return $this->apiResponseMessage(1,'deleted successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete_image(Request $request){
        $this->portfolioImageRepo->deleteImage($request->image_id);
        return $this->apiResponseMessage(1,'deleted successfully');
    }

    private function validatePortfolio($payload){
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
