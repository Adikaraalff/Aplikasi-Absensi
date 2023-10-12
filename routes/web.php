<?php

use App\Http\Controllers\AbsentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::resource('absents', AbsentController::class);
Route::resource('appointments', AppointmentController::class);
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class,'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class,'registration'])->name('register');
Route::post('post-registration',[AuthController::class, 'postRegistration'])->name('register.post');
Route::get('dashboard', [AuthController::class,'dashboard']);
Route::get('logout', [AuthController::class,'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
