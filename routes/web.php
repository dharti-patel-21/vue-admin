<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardStatController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ProfileController;

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

//Route::get('/admin/dashboard', function() {
//    return view('dashboard');
//});
Route::get('/api/stats/appointment', [DashboardStatController::class, 'appointments']);
Route::get('/api/stats/users', [DashboardStatController::class, 'users']);

Route::get('/api/settings',[SettingsController::class, 'index']);
Route::post('/api/settings/update',[SettingsController::class, 'update']);

Route::get('/api/profile',[ProfileController::class, 'index']);

Route::get('/api/users', [UsersController::class, 'index']);
Route::post('/api/createUser', [UsersController::class, 'store']);

Route::get('/api/appointments', [AppointmentController::class, 'index']);
Route::get('/api/appointment-status', [AppointmentController::class, 'getAppointmentStatus']);
Route::post('/api/appointments/create', [AppointmentController::class, 'store']);
Route::get('/api/appointment/{appointment}/edit', [AppointmentController::class, 'edit']);
Route::put('/api/appointment/{appointment}/udpate', [AppointmentController::class, 'update']);
Route::delete('/api/appointment/{appointment}', [AppointmentController::class, 'destroy']);

Route::get('{view}',ApplicationController::class)->where('view','(.*)');

Route::get('/api/client',[ClientController::class, 'new']);

