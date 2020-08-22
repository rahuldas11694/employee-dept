<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => 'v1'], function () {
    Route::group(['namespace' => 'Api'], function () {

        Route::post('department', 'DepartmentsController@createDept');
        Route::put('department/{dept_id}', 'DepartmentsController@updateDept');
        Route::get('departments', 'DepartmentsController@getAllDepartments');
        Route::get('department/{dept_id}', 'DepartmentsController@getDepartment');

        Route::post('employee', 'EmployeesController@createEmp');
        Route::put('employee/{emp_id}', 'EmployeesController@updateEmp');
        Route::get('employees', 'EmployeesController@getAllEmployees');
        Route::get('employee/{emp_id}', 'EmployeesController@getEmployee');

    });
});
