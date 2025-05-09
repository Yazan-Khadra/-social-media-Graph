<?php

namespace App;

trait JsonResponseTrait
{
    function JsonResponse($message,$status){
        $data = ['data' => $message , 'status' =>$status];
        return response() ->json($data);
    }
}
