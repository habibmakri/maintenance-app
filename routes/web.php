<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\comptabiliteController;
use App\Http\Controllers\exploatationController;
use App\Http\Controllers\gestionController;
use App\Http\Controllers\mainController;
use App\Http\Controllers\maintenanceController;
use App\Http\Controllers\personelleController;
use App\Http\Middleware\CheckUser;
use App\Http\Middleware\rolesMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/login', [Authcontroller::class, 'login'])->name('login');
Route::post('/login', [Authcontroller::class, 'dologin']);
Route::delete('/logout', [Authcontroller::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    return redirect()->route('login');
})->name('index');

Route::prefix('/app')->controller(mainController::class)->name('app.')->middleware(CheckUser::class)->group(function(){
    Route::get('/','main')->name('main');
    
    Route::prefix('/')->controller(MaintenanceController::class)->name('maintenance.')->group(function () {
        Route::get('maintenance_in','maintenance_in')->name('maintenance_in')->middleware('rolesMiddleware:maintenance_in');
        Route::post('maintenance_in','insertFichemaintenance');
        Route::get('maintenance/check-buses',  'checkBuses')->name('check_buses');
        Route::get('maintenance_show','maintnenance_show')->name('maintenance_show')->middleware('rolesMiddleware:maintenance_out');
        Route::post('maintenance/pdf', 'generatePDF')->name('pdf');
        Route::post('maintenance/gasoilepdf', 'generate_gasoile_PDF')->name('gasoilepdf');
        Route::post('maintenance/gasoileexcel', 'generate_gasoile_EXCEL')->name('gasoileexcel');
        Route::post('maintenance/km100pdf', 'generate_km100_PDF')->name('km100pdf');
        Route::post('maintenance/etatnreparatiopdf', 'generate_etat_nreparatiopdf')->name('etatnreparatiopdf');
        Route::post('maintenance/excel', 'generateEXCEL')->name('excel');
        Route::post('maintenance/etatkilometrage', 'generateETATKilometrage')->name('etatkilometrage');
        Route::get('maintenance/refreshfichtable', 'refreshfichtable');
        Route::get('maintenance_fix','maintnenance_fix')->name('maintenance_fix')->middleware('rolesMiddleware:maintenance_fix');
        Route::get('maintenance/refreshfixtable', 'refreshfixtable');
        Route::post('maintenance/deletefiche:{id}', 'deletefiche');
        Route::get('editfiche:{id}', 'editfiche')->name('maintenance_edit')->middleware('rolesMiddleware:maintenance_fix');
        Route::post('editfiche:{id}', 'doeditfiche');
        Route::get('maintenance_export','login_form')->name('maintenance_export');
        Route::get('maintenance_panne','maintenance_panne')->name('maintenance_panne')->middleware('rolesMiddleware:maintenance_panne');
        Route::post('maintenance_panne','resoudre_maintenance_panne');
        Route::post('maintenance/ajouter_ndpanne', 'ajouter_ndpanne')->name('ajouter_ndpanne');
        Route::post('maintenance/suivibus_pdf', 'generate_suivibus_pdf')->name('suivibus_pdf');
        Route::post('maintenance/suivijournaliere_pdf', 'generate_suivijournaliere_pdf')->name('suivijournaliere_pdf');
        Route::post('maintenance/pannerapport_pdf', 'generate_Pannerapport_PDF')->name('panne_pdf');
    });
    Route::prefix('/')->controller(gestionController::class)->name('gestion.')->group(function () {
        Route::get('manage_user','manage_user')->name('manage_user')->middleware('rolesMiddleware:manage_user');
        Route::get('manage_user/add_user','add_user')->name('add_user')->middleware('rolesMiddleware:manage_user');
        Route::post('manage_user/add_user','do_add_user');
        Route::post('manage_user/deleteuser:{id}', 'delete_user');
        Route::get('manage_user/edit_user:{id}','edit_user')->name('edit_user')->middleware('rolesMiddleware:manage_user');
        Route::post('manage_user/edit_user:{id}','do_edit_user');
        Route::get('manage_bus','manage_bus')->name('manage_bus')->middleware('rolesMiddleware:manage_bus');
        Route::get('manage_bus/add_bus','add_bus')->name('add_bus')->middleware('rolesMiddleware:manage_bus');
        Route::post('manage_bus/add_bus','do_add_bus');
        Route::get('manage_bus/edit_bus:{id}','edit_bus')->name('edit_bus')->middleware('rolesMiddleware:manage_bus');
        Route::post('manage_bus/edit_bus:{id}','do_edit_bus');
        Route::get('manage_ligne','manage_ligne')->name('manage_ligne')->middleware('rolesMiddleware:manage_ligne');
        Route::get('manage_ligne/add_ligne','add_ligne')->name('add_ligne')->middleware('rolesMiddleware:manage_ligne');
        Route::post('manage_ligne/add_ligne','do_add_ligne');
        Route::get('manage_ligne/edit_ligne:{id}','edit_ligne')->name('edit_ligne')->middleware('rolesMiddleware:manage_ligne');
        Route::post('manage_ligne/edit_ligne:{id}','do_edit_ligne');
        Route::get('manage_panne','manage_panne')->name('manage_panne')->middleware('rolesMiddleware:manage_panne');
        Route::get('manage_panne/add_panne','add_panne')->name('add_panne')->middleware('rolesMiddleware:manage_panne');
        Route::post('manage_panne/add_panne','do_add_panne');
        Route::post('manage_panne/deletepanne:{id}', 'delete_panne');
        Route::get('manage_piece','manage_piece')->name('manage_piece')->middleware('rolesMiddleware:manage_piece');
        Route::get('manage_piece/add_piece','add_piece')->name('add_piece')->middleware('rolesMiddleware:manage_piece');
        Route::post('manage_piece/add_piece','do_add_piece');
        Route::post('manage_piece/deletepiece:{id}', 'delete_piece');
        // Route::post('user_in','insertUser');        
    });
    Route::prefix('/')->controller(personelleController::class)->name('personelle.')->group(function () {
        Route::get('personelle_stat','personelle_stat')->name('statistiques')->middleware('rolesMiddleware:personelle_stat');
        
    });
    Route::prefix('/')->controller(comptabiliteController::class)->name('comptabilite.')->group(function () {
        Route::get('comptabilite_stat','comptabilite_stat')->name('statistiques')->middleware('rolesMiddleware:comptabilite_stat');
        
    });
    Route::prefix('/')->controller(exploatationController::class)->name('exploatation.')->group(function () {
        Route::get('exploatation_stat','exploatation_stat')->name('statistiques')->middleware('rolesMiddleware:exploatation_stat');
        
    });


});

