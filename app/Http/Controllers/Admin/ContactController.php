<?php

namespace App\Http\Controllers\Admin;

use App\Core\AppResult;
use App\Http\Collections\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Repos\ContactRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Validator, Auth, Artisan, Hash, File, Crypt;

class ContactController extends Controller
{
    use \App\Traits\ApiResponseTrait;
    private $contactRepo;

    public function __construct(ContactRepo $contactRepo)
    {
        $this->contactRepo=$contactRepo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get(Request $request){
        $cities=$this->contactRepo->get($request);
        return $this->apiResponseData(new ContactCollection($cities));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function single(Request $request){
        $response=$this->contactRepo->getContactById($request->contact_id);
        return $this->apiResponseData(new ContactResource($response->data));
    }



    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request){
        $response=$this->contactRepo->getContactById($request->contact_id);
        $this->contactRepo->delete($response->data);
        return $this->apiResponseMessage(1,'deleted successfully');
    }

}
