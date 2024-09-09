<?php

namespace App\Traits;

trait apiResponseTrait{
    public function apiResponse($data,$message,$status)
    {
        $array = [
            $data,
            $message,
        ];
        return response()->json($array , $status);
    }
}