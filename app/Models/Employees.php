<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{

    protected $table = "employees";
    protected $primaryKey = "emp_id"; 

    public function insertEmp(array $tobeInserted){
        return self::insertGetId($tobeInserted);
    }

    public function getEmployeeById(int $id){
        return self::where("emp_id", $id)
                ->first();
    }

    public function getAllEmps(){
        return self::all();
    }

    public function updateWhere(array $where = [], $tobeUpdated){

        $flag = self::where($where)
                ->update($tobeUpdated);
        
        return $flag;                
    }

    public function deleteEmp(int $empId){
        return self::findOrFail($empId)->delete();
    }
}
