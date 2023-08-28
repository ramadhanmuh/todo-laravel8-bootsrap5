<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\User\HomeController as UserHomeController;
use App\Http\Controllers\User\LogoutController as UserLogoutController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\ChangePasswordController as UserChangePasswordController;
use App\Http\Controllers\User\TaskController as UserTaskController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Admin\LogoutController as AdminLogoutController;
use App\Http\Controllers\Admin\ResetPasswordController as AdminResetPasswordController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ChangePasswordController as AdminChangePasswordController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\LoginController as OwnerLoginController;

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
        ->middleware('loggedoutuser')
        ->name('home');

Route::prefix('login')->group(function () {
        Route::middleware('loggedoutuser')->group(function () {
                Route::get('/', [LoginController::class, 'show'])
                        ->name('login.show');
                Route::post('/', [LoginController::class, 'authenticate'])
                        ->name('login.authenticate')
                        ->middleware(['throttle:5,5']);
        });
});

Route::prefix('forgot-password')->group(function () {
        Route::middleware('loggedoutuser')->group(function () {
                Route::get('/', [ForgotPasswordController::class, 'show'])
                        ->name('forgot-password.show');
                Route::post('/', [ForgotPasswordController::class, 'send'])
                        ->name('forgot-password.send')
                        ->middleware(['throttle:3,5']);
        });
});

Route::prefix('register')->group(function () {
        Route::middleware('loggedoutuser')->group(function () {
                Route::get('/', [RegisterController::class, 'show'])
                        ->name('register.show');
                Route::post('/', [RegisterController::class, 'save'])
                        ->name('register.save')
                        ->middleware(['throttle:3,5']);
        });
});

Route::prefix('reset-password')->group(function () {
        Route::middleware('loggedoutuser')->group(function () {
                Route::get('/', [ResetPasswordController::class, 'show'])
                        ->name('reset-password.show');

                Route::post('/', [ResetPasswordController::class, 'save'])
                        ->name('reset-password.save')
                        ->middleware(['throttle:3,5']);
        });
});

Route::get('users/{id}/verification/{token}', [VerificationController::class, 'verify'])
        ->name('verification');

Route::prefix('user')->group(function () {
        Route::middleware('userisloggedin')->group(function () {
                Route::name('user.')->group(function () {
                        Route::get('home', [UserHomeController::class, 'index'])
                                ->name('home');
        
                        Route::post('logout', UserLogoutController::class)
                                ->name('logout');

                        Route::prefix('profile')->group(function () {
                                Route::controller(UserProfileController::class)->group(function () {
                                        Route::name('profile.')->group(function () {
                                                Route::get('/', 'index')
                                                        ->name('index');
                                                Route::get('edit', 'edit')
                                                        ->name('edit');
                                                Route::put('edit', 'update')
                                                        ->name('update');
                                        });
                                });
                        });

                        Route::prefix('change-password')->group(function () {
                                Route::controller(UserChangePasswordController::class)->group(function () {
                                        Route::name('change-password.')->group(function () {
                                                Route::get('/', 'edit')
                                                        ->name('edit');
                                                Route::put('edit', 'update')
                                                        ->name('update');
                                        });
                                });
                        });

                        Route::resource('tasks', UserTaskController::class);
                });

        });
});

Route::prefix('admin')->group(function () {
        Route::name('admin.')->group(function () {
                Route::middleware('loggedoutadmin')->group(function () {
                        Route::controller(AdminLoginController::class)->group(function () {
                                Route::name('login.')->group(function () {
                                        Route::get('/', 'show')->name('show');
                                        Route::post('/', 'authenticate')->name('authenticate')
                                                                        ->middleware('throttle:5,5');
                                });
                        });

                        Route::prefix('forgot-password')->group(function () {
                                Route::name('forgot-password.')->group(function () {
                                        Route::controller(AdminForgotPasswordController::class)->group(function () {
                                                Route::get('/', 'show')->name('show');
                                                Route::post('/', 'send')->name('send')
                                                                        ->middleware('throttle:3,5');
                                        });
                                });
                        });

                        Route::prefix('reset-password')->group(function () {
                                Route::name('reset-password.')->group(function () {
                                        Route::get('/', [AdminResetPasswordController::class, 'show'])
                                                ->name('show');
                        
                                        Route::post('/', [AdminResetPasswordController::class, 'save'])
                                                ->name('save')
                                                ->middleware(['throttle:3,5']);
                                });
                        });
                });

                Route::middleware('adminisloggedin')->group(function () {
                        Route::post('logout', AdminLogoutController::class)->name('logout');

                        Route::prefix('dashboard')->group(function () {
                                Route::name('dashboard.')->group(function () {
                                        Route::controller(AdminDashboardController::class)->group(function () {
                                                Route::get('/', 'index')->name('index');
                                                Route::get('total-tasks-daily', 'getTotalTodayTasks')->name('totalTasksDaily');
                                                Route::get('total-tasks-monthly', 'getCurrentMonthTotalTasks')->name('totalTasksMonthly');
                                        });
                                });
                        });

                        Route::prefix('profile')->group(function () {
                                Route::name('profile.')->group(function () {
                                        Route::controller(AdminProfileController::class)->group(function () {
                                                Route::get('/', 'index')->name('index');
                                                Route::get('edit', 'edit')->name('edit');
                                                Route::put('edit', 'update')->name('update');
                                        });
                                });
                        });

                        Route::prefix('change-password')->group(function () {
                                Route::controller(AdminChangePasswordController::class)->group(function () {
                                        Route::name('change-password.')->group(function () {
                                                Route::get('/', 'edit')
                                                        ->name('edit');
                                                Route::put('edit', 'update')
                                                        ->name('update');
                                        });
                                });
                        });

                        Route::prefix('application')->group(function () {
                                Route::controller(AdminApplicationController::class)->group(function () {
                                        Route::name('application.')->group(function () {
                                                Route::get('/', 'index')
                                                        ->name('index');
                                                Route::get('edit', 'edit')
                                                        ->name('edit');
                                                Route::put('edit', 'update')
                                                        ->name('update');
                                        });
                                });
                        });

                        Route::resource('users', AdminUserController::class);
                });

        });
});

Route::prefix('owner')->group(function () {
        Route::name('owner.')->group(function () {
                Route::middleware(['loggedoutowner'])->group(function () {
                        Route::name('login.')->group(function () {
                                Route::controller(OwnerLoginController::class)->group(function () {
                                        Route::get('/', 'show')->name('show');
                                        Route::post('/', 'authenticate')->name('authenticate')
                                                                        ->middleware('throttle:5,5');
                                });
                        });
                });

                Route::middleware('ownerisloggedin')->group(function () {
                        Route::prefix('dashboard')->group(function () {
                                Route::name('dashboard.')->group(function () {
                                        Route::controller(OwnerDashboardController::class)->group(function () {
                                                Route::get('/', 'index')
                                                        ->name('index');
                                                Route::get('total-users', 'totalUsers')
                                                        ->name('total-users');
                                                Route::get('total-administrators', 'totalAdministrators')
                                                        ->name('total-administrators');
                                                Route::get('total-owners', 'totalOwners')
                                                        ->name('total-owners');
                                                Route::get('total-tasks-today', 'totalTasksToday')
                                                        ->name('total-tasks-today');
                                                Route::get('total-tasks-this-month', 'totalTasksThisMonth')
                                                        ->name('total-tasks-this-month');
                                                Route::get('total-tasks-this-year', 'totalTasksThisYear')
                                                        ->name('total-tasks-this-year');
                                                Route::get('total-daily-tasks', 'totalDailyTasks')
                                                        ->name('total-daily-tasks');
                                                Route::get('total-monthly-tasks', 'totalMonthlyTasks')
                                                        ->name('total-monthly-tasks');
                                                Route::get('total-annual-tasks', 'totalAnnualTasks')
                                                        ->name('total-annual-tasks');
                                        });
                                });
                        });
                });
        });
});

