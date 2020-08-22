<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = "departments";

    public function getAll(){
        return self::all();
    }

    public function insertDept(array $tobeInserted){
        return self::insertGetId($tobeInserted);
    }

    public function checkIfDeptExists(string $deptName){
        
        return self::where('dept_name', '=', $deptName)
                    ->first();
    }

    public function getWhere(array $where = []){
        return self::where($where)
                    ->get();
    }

    public function updateWhere(array $where = [], $tobeUpdated){

        $flag = self::where($where)
                ->update($tobeUpdated);
        
        return $flag;                
    }

    public function getDeptById(int $id){
        return self::where("dept_id", $id)
                    ->first();
    }
}
