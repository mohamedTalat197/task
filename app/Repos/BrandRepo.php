<?php

namespace App\Repos;

use App\Core\AppResult;
use App\Helpers\ImageHelper;
use App\Helpers\NumberHelper;
use App\Models\Brand;
use Subscription\Models\Country;
use Validator, Auth, Artisan, Hash, File, Crypt;

class BrandRepo
{

    /**
     * @param $filter
     * @return mixed
     */
    public function get($filter)
    {
        $brands=Brand::orderBy('id','desc');
        $limit=$filter->limit ? $filter->limit : 10;
        if($filter->user_id)
            $brands=$brands->where('user_id',$filter->user_id);
        $brands=$brands->paginate($limit);
        return $brands;
    }


    /**
     * @param $id
     * @return AppResult
     */
    public function getBrandById($id)
    {
        $brand=Brand::findOrfail($id);
        return AppResult::success($brand);
    }
    /**
     * @param $payload
     * @return Brand
     */
    public function create($payload)
    {
        $brand = new Brand();
        $brand->name_ar = $payload->name_ar;
        $brand->name_en = $payload->name_en;
        $brand->user_id = $payload->user_id;
        if($payload->image)
            $brand->image=ImageHelper::getInstance()->saveImage('Branch',$payload->image);
        $brand->save();
        return $brand;
    }

    /**
     * @param $payload
     * @param $brand
     * @return mixed
     */
    public function update($payload,$brand)
    {
        if(isset($payload->name_ar))
            $brand->name_ar = $payload->name_ar;
        if(isset($payload->name_en))
            $brand->name_en = $payload->name_en;
        if($payload->image) {
            ImageHelper::getInstance()->deleteFile('Brand',$brand->image);
            $brand->image=ImageHelper::getInstance()->saveImage('Brand',$brand->image);
        }
        $brand->save();
        return $brand;
    }


    /**
     * @param $brand
     * @return void
     */
    public function delete($brand)
    {
        ImageHelper::getInstance()->deleteFile('Brand',$brand->image);
        $brand->delete();
    }







}
