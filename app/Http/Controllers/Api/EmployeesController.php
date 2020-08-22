<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Api\DepartmentsController;

use App\Models\{Departments, Employees};

class EmployeesController extends DepartmentsController
{
    public function __construct(Employees $empModel){
        parent::__construct(new Departments);
        
        $this->empModel = $empModel;
    }

    public function createEmp(Request $request){
        $inputs     = $request->input();
        $emp_name   = $inputs["emp_name"] ?? "";
        $dept_id    = $inputs["dept_id"] ?? "";

        $emp_addresses          = isset($inputs["addresses"]) ? json_encode($inputs["addresses"]) : null;
        $emp_contact_numbers    = isset($inputs["contact_numbers"]) ? json_encode($inputs["contact_numbers"]) : null;

        
        // validate fields
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'emp_name' => 'required',
                    'dept_id' => 'required|numeric',
                    'addresses' => 'required|sometimes',
                    'contact_numbers' => 'required|sometimes'
                ]
            );
            // if validation fails then throw error which is a user error
            if ($validator->fails()) {
                return $this->respondWithValidationError($validator->errors());
            }
            $tobe_inserted = [
                            'emp_name' => $emp_name,
                            'fk_dept_id' => $dept_id,
                            'addresses' => $emp_addresses,
                            'contact_numbers' => $emp_contact_numbers
                        ];
            // dd($inputs, $tobe_inserted);

            if(!$this->deptModel->getDeptById($dept_id)){
                return $this->respondOk("Department does not exists.", $this->userCode);
            }

            $emp_id = $this->empModel->insertEmp($tobe_inserted);

            return $this->respondOk("Employee Created successfully.", $this->successCode);

        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    
    public function updateEmp(Request $request){

    }
    
    public function getAllEmployees(Request $request){
        
    }
    
    public function getEmployee(Request $request){
        $inputs    = $request->input();
        $emp_id    = (int) $request->route('emp_id') ?? 0;

        $employee = $this->empModel->getEmployeeById($emp_id);

        if(!$employee){
            return $this->respondOk("Employee does not exists.", $this->userCode);
        }

        $employee->dept_id          = $employee->fk_dept_id;
        $employee->addresses        = json_decode($employee->addresses);
        $employee->contact_numbers  = json_decode($employee->contact_numbers);

        $employee = collect($employee)->except(['fk_dept_id', 'created_at', 'updated_at']);

        return $this->respondOk($employee, $this->successCode);
    }
}
