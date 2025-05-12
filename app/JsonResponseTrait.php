<?php

namespace App;

trait JsonResponseTrait
{
    public function JsonResponse($message,$status){
        $data = ['message' => $message , 'status' =>$status];
        return response()->json($data);
    }
    function JsonResponseWithData($message,$data,$status){
        $FinalData = ['message' => $message ,'data'=>$data, 'status' =>$status];
        return response()->json($data);
    }
}
