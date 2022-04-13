<?php

use App\Http\Controllers\Api\EmployeeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(EmployeeController::class)->group(function(){

    Route::get('/employees','index');
    Route::post('/employee','store');
    Route::post('/worked-hours','workedHours');
    Route::get('/worked_hours_by_employee','workedEmployee');
    Route::get('/payment_employee','paymentEmployee');


});
