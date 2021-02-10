<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home'); // --> {{ route('home') }}
Route::middleware(['auth','admin'])->namespace('Admin')->group(function () {
    // Specialty
    Route::get('/specialties', 'SpecialtyController@index');
    Route::get('/specialties/create', 'SpecialtyController@create');
    Route::get('/specialties/{specialty}/edit', 'SpecialtyController@edit');
    Route::post('/specialties', 'SpecialtyController@store');
    Route::put('/specialties/{specialty}', 'SpecialtyController@update');
    Route::delete('/specialties/{specialty}', 'SpecialtyController@destroy');

    // Doctors
    Route::resource('doctors', 'DoctorController'); // Crea rutas para CRUD doctores (get, post, put, delete)

    // Patients
    Route::resource('patients', 'PatientController');// Crea rutas para CRUD pacientes (get, post, put, delete)
});
Route::middleware(['auth','doctor'])->namespace('Doctor')->group(function () {
    // Specialty
    Route::get('/schedule', 'ScheduleController@edit');    
    Route::post('/schedule', 'ScheduleController@store');    
});

Route::get('/appointments/create', 'AppointmentController@create');
Route::post('/appointments', 'AppointmentController@store');