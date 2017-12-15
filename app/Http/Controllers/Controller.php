<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Response; 


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // protected $resultFormat = array(
    //     'message'      => ':message',
    //     'data' 	       => ':data',        
    //     'code'         => ':code',
    // );

    // public function setResponseFormat($statusCode = 200, $message, $data = NULL, $code = 0){
    //     $this->resultFormat = [
    //         'message'  => $message,
    //         'data'	   => $data,
    //         'code'     => $code,
    //     ];

    //     return Response::json($this->resultFormat, $statusCode)
    //         ->header('Access-Control-Allow-Headers', '*')
    //         ->header('Access-Control-Allow-Origin', '*')
    //         ->header('Access-Control-Allow-Methods', '*')
    //         ->header('Content-Type', 'application/json')
    //         ->header('Access-Control-Allow-Credentials', true);
 
    // }
}
