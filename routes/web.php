<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\User\HomeController as UserHomeController;

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

Route::get('/', [HomeController::class, 'index'])
        ->name('home');

Route::prefix('login')->group(function () {
        Route::get('/', [LoginController::class, 'show'])->name('login.show');
        Route::post('/', [LoginController::class, 'authenticate'])->name('login.authenticate')
                                                                ->middleware(['throttle:100,5']);
});

Route::prefix('register')->group(function () {
        Route::get('/', [RegisterController::class, 'show'])->name('register.show');
        Route::post('/', [RegisterController::class, 'save'])->name('register.save')
                                                                ->middleware(['throttle:5,5']);
});

Route::prefix('user')->group(function () {
        Route::get('home', [UserHomeController::class, 'index'])->name('user.home')
                                                                ->middleware(['userisloggedin']);
});

Route::get('users/{id}/verification/{token}', [VerificationController::class, 'verify'])
        ->name('verification');

