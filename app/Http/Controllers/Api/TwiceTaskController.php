<?php

namespace App\Http\Controllers\Api;

use App\Core\AppResult;
use App\Http\Collections\BrandCollection;
use App\Http\Resources\BrandResource;
use App\Repos\BrandRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Validator, Auth, Artisan, Hash, File, Crypt;

class TwiceTaskController extends Controller
{
    use \App\Traits\ApiResponseTrait;


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeDuplicates(Request $request)
    {
        $nums = json_decode($request->input('nums'));
        $count = 0;
        $expectedNums = [];
        for ($i = 0; $i < count($nums); $i++) {
            if ($i < count($nums) - 1 && $nums[$i] === $nums[$i + 1])
                continue;
            $expectedNums[$count] = $nums[$i];
            $count++;
        }
        $expectedNums = array_values($expectedNums);
        return response()->json([
            'count' => $count,
            'expectedNums' => $expectedNums,
        ]);
    }

}
