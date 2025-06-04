<?php
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardAnalyticsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::group(['middleware' => 'api', 'prefix' => 'v1'], function () {
    Route::get('user/', function (Request $request) {
        return $request->user();
    });
    Route::get('verify-email', App\Http\Controllers\Auth\VerifyEmailController::class);





    Route::post('save-employee', [EmployeeController::class, 'store']);
    Route::get('get-employees', [EmployeeController::class, 'show']);
    Route::delete('delete-employee', [EmployeeController::class, 'destroy']);
    Route::post('update-employee', [EmployeeController::class, 'update']);
    Route::get('get-employee-details', [EmployeeController::class, 'getEmployeeDetails']);


    Route::post('save-company', [CompanyController::class, 'store']);
    Route::get('get-compnies', [CompanyController::class, 'show']);
    Route::delete('delete-company', [CompanyController::class, 'destroy']);
    Route::get('get-compny-details', [CompanyController::class, 'getCompanyDetails']);
    Route::post('update-company', [CompanyController::class, 'update']);

    Route::get('get-dashboard-analytics', [DashboardAnalyticsController::class, 'index']);

    Route::post('save-todo', [TodoController::class, 'store']);
    Route::get('get-todos', [TodoController::class, 'show']);
    Route::post('update-todo', [TodoController::class, 'update']);
    Route::get('get-todo-details', [TodoController::class, 'getTodoDetails']);
    Route::delete('delete-todo', [TodoController::class, 'destroy']);










});