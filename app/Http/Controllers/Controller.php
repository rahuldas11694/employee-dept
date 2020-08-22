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
    /**
    * @var $responseNo
    * @var_info  -1 is system error, 0 is success and 1 is user error
    */
    protected $responseNo = 0; 

    public function __construct(){

    } 

    private function sendResponse($data){
        if ($this->httpStatus == 200 && (gettype($data) == 'array' || gettype($data) == 'object') ) {
            return response()
                ->json([
                    'responseNo' => $this->responseNo,
                    'data' => $data
                ])
                ->setStatusCode($this->httpStatus);
        }
        return response()
            ->json([
                'responseNo' => $this->responseNo,
                'message' => $data
            ])
            ->setStatusCode($this->httpStatus);
    }

    public function respondWithError($message){
        $this->httpStatus = 500;
        // $this->status = 0;
        $this->responseNo = -1;
        return $this->sendResponse($message);
    }

    public function respondOk($data, $responseNo = 0) {
        $this->httpStatus = 200;
        $this->responseNo = $responseNo;
        return $this->sendResponse($data);
    }

    public function respondWithValidationError($message) {
        $this->httpStatus = 422;
        $this->responseNo = -1;
        // dd(gettype($message));
        if (gettype($message) == 'string' || gettype($message) == 'array') {
            return $this->sendResponse($message);
        }
        return $this->sendResponse($message);
    }
}
