<?php

namespace App\Repos;

use App\Core\AppResult;
use App\Helpers\ImageHelper;
use App\Helpers\NumberHelper;
use App\Models\Brand;
use App\Models\Campaign;
use Subscription\Models\Country;
use Validator, Auth, Artisan, Hash, File, Crypt;

class CampaignRepo
{

    /**
     * @param $filter
     * @return mixed
     */
    public function get($filter)
    {
        $campaigns=Campaign::orderBy('id','desc');
        $limit=$filter->limit ? $filter->limit : 10;
        if($filter->user_id)
            $campaigns=$campaigns->where('user_id',$filter->user_id);
        if($filter->type)
            $campaigns=$campaigns->where('campaignType',$filter->type);
        $campaigns=$campaigns->paginate($limit);
        return $campaigns;
    }


    /**
     * @param $id
     * @return AppResult
     */
    public function getCampaingById($id)
    {
        $campaign=Campaign::findOrfail($id);
        return AppResult::success($campaign);
    }

    /**
     * @param $payload
     * @return Campaign
     */
    public function create($payload)
    {
        $campaign = new Campaign();
        $campaign->name_ar = $payload->name_ar;
        $campaign->name_en = $payload->name_en;
        $campaign->desc_ar = $payload->desc_ar;
        $campaign->desc_en = $payload->desc_en;
        $campaign->campaignType = $payload->type;
        $campaign->brand_id = $payload->brand_id;
        $campaign->user_id = $payload->user_id;
        if($payload->video)
            $campaign->video=ImageHelper::getInstance()->saveImage('Campaign',$payload->video);
        $campaign->save();
        return $campaign;
    }

    /**
     * @param $payload
     * @param $campaign
     * @return mixed
     */
    public function update($payload,$campaign)
    {
        if(isset($payload->name_ar))
            $campaign->name_ar = $payload->name_ar;
        if(isset($payload->name_en))
            $campaign->name_en = $payload->name_en;
        if(isset($payload->desc_ar))
            $campaign->desc_ar = $payload->desc_ar;
        if(isset($payload->desc_en))
            $campaign->desc_en = $payload->desc_en;
        if(isset($payload->type))
            $campaign->campaignType = $payload->type;
        if(isset($payload->brand_id))
            $campaign->brand_id = $payload->brand_id;
        if($payload->video) {
            ImageHelper::getInstance()->deleteFile('Campaign',$campaign->video);
            $campaign->video=ImageHelper::getInstance()->saveImage('Campaign',$campaign->video);
        }
        $campaign->save();
        return $campaign;
    }


    /**
     * @param $campaign
     * @return void
     */
    public function delete($campaign)
    {
        ImageHelper::getInstance()->deleteFile('Campaign',$campaign->video);
        $campaign->delete();
    }







}
