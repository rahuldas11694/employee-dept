<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Api\v1\DepartmentsController;

use App\Models\{Departments, Employees};

/**
* @group Company Employees manageent
*
* Employee APIs
*/

class EmployeesController extends DepartmentsController
{
    public function __construct(Employees $empModel){
        parent::__construct(new Departments);

        $this->empModel = $empModel;
    }


    /**
    * Create an Employee
    * 
    * @bodyParam  emp_name string required The name of the Employee. Example:rahul das
    * @bodyParam  dept_id Example: 1
    * @bodyParam  addresses[] Example: Address 1
    * @bodyParam  addresses[] Example: add2
    * @bodyParam  contact_numbers[] Example: 123123123
    * @bodyParam  contact_numbers[] Example: 897875555
    *
    * @response  {
    *    "responseNo": 0,
    *    "message": "Employee Created successfully."
    *   }
    *
    */

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

    /**
    * Update an Employee
    * 
    * @bodyParam  emp_name string required The name of the Employee. Example:rahul das
    * @bodyParam  dept_id Example: 1
    * @bodyParam  addresses[] Example: Address 1
    * @bodyParam  addresses[] Example: add2
    * @bodyParam  contact_numbers[] Example: 123123123
    * @bodyParam  contact_numbers[] Example: 897875555
    *
    * @response  {
    *    "responseNo": 0,
    *    "message": "Employee updated successfully."
    *   }
    *
    */
    
    public function updateEmp(Request $request){
        $inputs     = $request->input();
        $emp_id     = (int) $request->route('emp_id') ?? 0;

        $dept_id    = $inputs['dept_id'] ?? 0;
        $emp_name   = $inputs['emp_name'] ?? "";

        $emp_addresses          = isset($inputs["addresses"]) ? json_encode($inputs["addresses"]) : null;
        $emp_contact_numbers    = isset($inputs["contact_numbers"]) ? json_encode($inputs["contact_numbers"]) : null;
        // validate fields
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'emp_name' => 'required',
                    'dept_id' => 'required',
                    'addresses' => 'required|sometimes',
                    'contact_numbers' => 'required|sometimes'
                ]
            );
            // if validation fails then throw error
            if ($validator->fails()) {
                return $this->respondWithValidationError($validator->errors());
            }elseif($emp_name == ""){
                return $this->respondOk("Employee name can not be blank", $this->userCode);
            }

            $where = ['emp_id' => $emp_id];

            $tobe_updated = [
                            'emp_name' => $emp_name,
                            'fk_dept_id' => $dept_id,
                            'addresses' => $emp_addresses,
                            'contact_numbers' => $emp_contact_numbers,
                        ];

            // check if emp exists if yes then update emp else err
            if(!$this->empModel->getEmployeeById($emp_id)){
                return $this->respondOk("Employee does not exists.", $this->userCode);
            }
            // also check if dept exists, update only if dept exists
            if(!$this->deptModel->getDeptById($dept_id)){
                return $this->respondOk("Department does not exists.", $this->userCode);
            }

            $emp_id = $this->empModel->updateWhere($where, $tobe_updated);

            return $this->respondOk("Employee updated successfully.", $this->successCode);

        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
    * Get All Employees
    * 
    * @urlParam  dept_id required The ID of the department. Example: 111
    *
    * @response  {
    *
    *    "responseNo": 0,
    *
    *    "data": [
    *
    *       {
    *
    *         "emp_id": 2,
    *         "emp_name": "BOB",
    *         "addresses": [
    *            "add1",
    *            "add2",
    *            "add3"
    *         ],
    *         "contact_numbers": [
    *            "12",
    *            "34",
    *            "45"
    *         ],
    *         "dept_id": 1
    *       },
    *       {
    *        "emp_id": 3,
    *        "emp_name": "Alice",
    *        "addresses": [
    *            "add1",
    *            "add2",
    *            "add3"
    *        ],
    *        "contact_numbers": [
    *            "12",
    *            "34",
    *            "45"
    *         ],
    *        "dept_id": 1
    *       }
    *     ]
    *    }
    *
    */
    
    public function getAllEmployees(Request $request){
        $emps = $this->empModel->getAllEmps();
        
        $emps->each(function($employee){
            
            $employee->dept_id          = $employee->fk_dept_id;
            $employee->addresses        = json_decode($employee->addresses);
            $employee->contact_numbers  = json_decode($employee->contact_numbers);

            unset($employee->fk_dept_id, $employee->created_at, $employee->updated_at);
        });

        return $this->respondOk($emps, $this->successCode);
    }
    
    /**
    * Get an Employee
    * 
    * @urlParam  emp_id required The ID of the employee. Example: 2

    * @response  {
    *
    *    "responseNo": 0,
    *
    *    "data": [
    *       {
    *         "emp_id": 2,
    *         "emp_name": "BOB",
    *         "addresses": [
    *            "add1",
    *            "add2",
    *            "add3"
    *         ],
    *         "contact_numbers": [
    *            "12",
    *            "34",
    *            "45"
    *         ],
    *         "dept_id": 1
    *       },
    *     ]
    *   }  
    */

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

    /**
    * Delete employee
    * 
    * @urlParam  emp_id int required The id of an Employee. Example:rahul das
    *
    * @response  {
    *    "responseNo": 0,
    *    "message": "Employee deleted successfully."
    *   }
    *
    */
    public function deleteEmployee(Request $request){
       $emp_id     = (int) $request->route('emp_id') ?? 0;

       $employee = $this->empModel->getEmployeeById($emp_id);

        if(!$employee){
            return $this->respondOk("Employee does not exists.", $this->userCode);
        }

        $this->empModel->deleteEmp($emp_id);

        return $this->respondOk("Employee deleted successfully.", $this->successCode);
    }

    /**
    * Find employees By name
    * 
    * @queryParam  name string required The searchable name of Employees. Example:ra
    *
    * @response  {
    *
    *    "responseNo": 0,
    *
    *    "data": [
    *
    *       {
    *         "emp_id": 2,
    *         "emp_name": "Ram",
    *         "addresses": [
    *            "add1",
    *            "add2",
    *            "add3"
    *         ],
    *         "contact_numbers": [
    *            "12",
    *            "34",
    *            "45"
    *         ],
    *         "dept_id": 1
    *       },
    *       {
    *        "emp_id": 3,
    *        "emp_name": "Rahul",
    *        "addresses": [
    *            "add1",
    *            "add2",
    *            "add3"
    *        ],
    *        "contact_numbers": [
    *            "12",
    *            "34",
    *            "45"
    *         ],
    *        "dept_id": 1
    *       }
    *     ]
    *    }
    *
    */

    public function searchEmployee(Request $request){
        $employee_str   = $request->name ?? "";
        $employee_name  = trim($employee_str);

        if($employee_name == "" || strlen($employee_name) < 2){
            return $this->respondOk("Please provide minimum 2 characters", $this->userCode);
        }

        $emps = $this->empModel->getEmployeesByName($employee_name);
        // dd($emps);
        $emps->each(function($employee){
            
            $employee->dept_id          = $employee->fk_dept_id;
            $employee->addresses        = json_decode($employee->addresses);
            $employee->contact_numbers  = json_decode($employee->contact_numbers);

            unset($employee->fk_dept_id, $employee->created_at, $employee->updated_at);
        });

        return $this->respondOk($emps, $this->successCode);
    }
}
