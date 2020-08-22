<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{

    protected $table = "employees";

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
}
