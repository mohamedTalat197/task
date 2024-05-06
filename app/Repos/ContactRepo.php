<?php

namespace App\Repos;

use App\Core\AppResult;
use App\Models\Contact;
use Validator,Auth,Artisan,Hash,File,Crypt;

class ContactRepo{
    use \App\Traits\ApiResponseTrait;

    /**
     * @param $filter
     * @return mixed
     */
    public function get($filter)
    {
        $contacts=Contact::orderBy('id','desc');
        $limit=$filter->limit ? $filter->limit : 10;
        $contacts=$contacts->paginate($limit);
        return $contacts;
    }


    /**
     * @param $id
     * @return AppResult
     */
    public function getContactById($id)
    {
        $contact=Contact::findOrfail($id);
        return AppResult::success($contact);
    }

    /**
     * @param $payload
     * @return Contact
     */
    public function create($payload)
    {
        $contact=new Contact();
        $contact->name=$payload->name;
        $contact->phone=$payload->phone;
        $contact->email=$payload->name;
        $contact->message=$payload->message;
        $contact->save();
        return $contact;
    }


    /**
     * @param $contact
     */
    public function delete($contact)
    {
        $contact->delete();
    }


}
