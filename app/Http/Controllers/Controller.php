<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $httpStatus = 200;
    protected $status = 1;

    public function __construct(){

    } 

    public function respondWithError($message){
        $this->httpStatus = 500;
        $this->status = 0;
        return $this->sendRes($message);
    }

    public function sendRes($data){
        if ($this->httpStatus == 200 && $this->status) {
            return response()
                ->json([
                    'status' => $this->status,
                    'data' => $data
                ])
                ->setStatusCode($this->httpStatus);
        }
        return response()
            ->json([
                'status' => $this->status,
                'message' => $data
            ])
            ->setStatusCode($this->httpStatus);
    }
}
