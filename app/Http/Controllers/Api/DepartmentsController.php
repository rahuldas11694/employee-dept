<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\{Departments};

class DepartmentsController extends Controller
{   

    protected $deptModel;

    protected $successCode = 0;
    protected $systemCode  = -1;
    protected $userCode    = 1;

    public function __construct(Departments $deptModel){
        
        $this->deptModel = $deptModel;
        // dd("parent");
    }

    public function index(){
        //
    }

    public function createDept(Request $request){
        $inputs     = $request->input();
        $dept_name   = $inputs["dept_name"] ?? "";
        // validate fields
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'dept_name' => 'required',
                ]
            );
            // if validation fails then throw error which is a user error
            if ($validator->fails()) {
                return $this->respondWithValidationError($validator->errors());
            }

            // check if dept exists if no then create dept
            if($this->deptModel->checkIfDeptExists($dept_name)){
                return $this->respondOk("Department already exists.", $this->userCode);
            }

            $tobe_inserted = [
                            'dept_name' => $dept_name 
                        ];

            $dept_id = $this->deptModel->insertDept($tobe_inserted);

            return $this->respondOk("Department Created successfully.", $this->successCode);

        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function updateDept(Request $request){
        $inputs     = $request->input();
        $dept_id    = (int) $request->route('dept_id') ?? 0;
        $dept_name  = $inputs["dept_name"] ?? "";
        // validate fields
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'dept_name' => 'required',
                ]
            );
            // if validation fails then throw error
            if ($validator->fails()) {
                return $this->respondWithValidationError($validator->errors());
            }

            // check if dept exists if no then create dept
            $where = ["dept_id" => $dept_id];

            if(!$this->deptModel->getDeptById($dept_id)){
                return $this->respondOk("Department does not exists.", $this->userCode);
            }

            $tobe_updated = [
                            'dept_name' => $dept_name 
                        ];

            $dept_id = $this->deptModel->updateWhere($where, $tobe_updated);

            return $this->respondOk("Department updated successfully.", $this->successCode);

        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function getAllDepartments(Request $request){

    }

    public function getDepartment(Request $request){

    }

    public function deleteDepartment(Request $request){
        $dept_id     = (int) $request->route('dept_id') ?? 0;

        $employee = $this->deptModel->getDeptById($dept_id);

        if(!$employee){
            return $this->respondOk("Department does not exists.", $this->userCode);
        }

        $this->deptModel->deleteDept($dept_id);

        return $this->respondOk("Department deleted successfully.", $this->successCode);    
    }
}
