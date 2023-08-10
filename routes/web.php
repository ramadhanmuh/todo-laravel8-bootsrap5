<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VerificationController;

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

Route::prefix('register')->group(function () {
    Route::get('/', [RegisterController::class, 'show'])->name('register.show');
    Route::post('/', [RegisterController::class, 'save'])->name('register.save')
                                                            ->middleware(['throttle:5,5']);
});

Route::get('users/{id}/verification/{token}', [VerificationController::class, 'verify'])
        ->name('verification');

