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
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');

// Public resources
Route::get('/specialties', 'SpecialtyController@index');   
Route::get('/specialties/{specialty}/doctors', 'SpecialtyController@doctors');   
Route::get('/schedule/hours', 'ScheduleController@hours');

Route::middleware('auth:api')->group(function (){
    Route::get('/user', 'UserController@show');
    Route::post('/logout', 'AuthController@logout');

    Route::get('/appointments', 'AppointmentController@index');
    Route::post('/appointments', 'AppointmentController@store');
});

