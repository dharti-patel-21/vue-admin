<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\AppointmentController;

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

Route::get('/api/users', [UsersController::class, 'index']);
Route::post('/api/createUser', [UsersController::class, 'store']);

Route::get('/api/appointments', [AppointmentController::class, 'index']);
Route::get('{view}',ApplicationController::class)->where('view','(.*)');