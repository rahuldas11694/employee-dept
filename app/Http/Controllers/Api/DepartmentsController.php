<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\{Departments};

class DepartmentsController extends Controller
{   

    protected $deptModel;

    public function __construct(Departments $deptModel){
        
        $this->deptModel = $deptModel;

    }

    public function index(){
        //
    }

    public function createDept(Request $request){
        $inputs = $request->input();
        // dd($request->all());
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'dept_name' => 'required',
                ]
            );

            if ($validator->fails()) {
                return $this->respondWithValidationError($validator->errors());
            }

            // check if dept exists if no then create dept
            $depts = $this->deptModel->getAll();
            return $depts;

        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
