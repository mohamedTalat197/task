<?php

namespace App\Http\Controllers\Api;

use App\Core\AppResult;
use App\Http\Collections\BrandCollection;
use App\Http\Collections\CampaignCollection;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use App\Repos\BrandRepo;
use App\Repos\CampaignRepo;
use App\Validations\CampaignValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Validator, Auth, Artisan, Hash, File, Crypt;

class CampaignController extends Controller
{
    use \App\Traits\ApiResponseTrait;
    private $campaignRepo;
    private $campaignValidation;

    public function __construct(CampaignRepo $campaignRepo , CampaignValidation $campaignValidation)
    {
        $this->campaignRepo=$campaignRepo;
        $this->campaignValidation=$campaignValidation;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $request['user_id'] =Auth::user()->id;
        $campaigns = $this->campaignRepo->get($request);
        return $this->apiResponseData(new CampaignCollection($campaigns));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function single(Request $request)
    {
        $response = $this->campaignRepo->getCampaingById($request->campaign_id);
        return $this->apiResponseData(new CampaignResource($response->data));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        App::setLocale($request->header('lang'));
        $validateCampaign = $this->campaignValidation->validate($request);
        if($validateCampaign->operationType==ERROR)
            return $this->apiResponseMessage(0,$validateCampaign->error,200);
        $request['user_id']=Auth::user()->id;
        $campaign = $this->campaignRepo->create($request);
        return $this->apiResponseData(new CampaignResource($campaign));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        App::setLocale($request->header('lang'));
        $response = $this->campaignRepo->getCampaingById($request->campaign_id);
        $campaign=$response->data;
        $validateCampaign = $this->campaignValidation->validate($request);
        if($validateCampaign->operationType==ERROR)
            return $this->apiResponseMessage(0,$validateCampaign->error,200);
        $data = $this->campaignRepo->update($request,$campaign);
        return $this->apiResponseData(new CampaignResource($data));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        App::setLocale($request->header('lang'));
        $response = $this->campaignRepo->getCampaingById($request->campaign_id);
        $this->campaignRepo->delete($response->data);
        return $this->apiResponseMessage(1,'deleted successfully');
    }

}
