<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = "departments";

    public function getAll(){
        return self::all();
    }
}
