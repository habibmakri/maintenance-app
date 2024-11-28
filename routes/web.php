<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\mainController;
use App\Http\Controllers\maintenanceController;
use App\Http\Middleware\CheckUser;
use App\Http\Middleware\rolesMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/login', [Authcontroller::class, 'login'])->name('login');
Route::post('/login', [Authcontroller::class, 'dologin']);
Route::delete('/logout', [Authcontroller::class, 'logout'])->name('logout')->middleware('auth');



Route::prefix('/app')->controller(mainController::class)->name('app.')->middleware(CheckUser::class)->group(function(){
    Route::get('/','main')->name('main');
    
    Route::prefix('/')->controller(MaintenanceController::class)->name('maintenance.')->group(function () {
        Route::get('maintenance_in','maintenance_in')->name('maintenance_in')->middleware('rolesMiddleware:maintenance_in');
        Route::post('maintenance_in','insertFichemaintenance');
        Route::get('maintenance/check-buses',  'checkBuses')->name('check_buses');
        Route::get('maintenance_show','maintnenance_show')->name('maintenance_show')->middleware('rolesMiddleware:maintenance_out');
        Route::get('maintenance/refreshfichtable', 'refreshfichtable');
        Route::get('maintenance_fix','maintnenance_fix')->name('maintenance_fix')->middleware('rolesMiddleware:maintenance_fix');
        Route::get('maintenance/refreshfixtable', 'refreshfixtable');
        Route::post('maintenance/deletefiche:{id}', 'deletefiche');
        Route::get('editfiche:{id}', 'editfiche')->name('maintenance_edit')->middleware('rolesMiddleware:maintenance_fix');
        Route::post('editfiche:{id}', 'doeditfiche');
        Route::get('maintenance_export','login_form')->name('maintenance_export');
    });


});

