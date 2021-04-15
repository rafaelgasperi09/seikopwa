<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
    return view('frontend.login');
});

/*************** USERS LOGIN PASSWORD ROUTES **************************************/
Route::get('/login', function () { return view('frontend.login');});
Route::post('login', array('as' => 'login','uses' => 'LoginController@login'));


Route::get('forgot_password/create', function () { return view('frontend.forgot_password'); });
Route::post('forgot_password/store', array('as' => 'forgot_password.store','uses' => 'ForgotPasswordController@store'));
Route::get('recovery_password/{id}/{token}', array('as' => 'forgot_password.edit', 'uses' => 'ForgotPasswordController@edit'));
Route::put('recovery_password/{id}/{token}', array('as' => 'forgot_password.update', 'uses' => 'ForgotPasswordController@update'));

Route::get('usuarios/{id}/update_password_view', array('as' => 'usuarios.update_password_view', 'uses' => 'UserController@updatePasswordView'))->middleware('sentinel.auth');
Route::put('usuarios/{id}/password', array('as' => 'usuarios.update_password', 'uses' => 'UserController@updatePassword'))->middleware('sentinel.auth');
/************************************************************************************/

Route::group(array('middleware' => ['sentinel.auth','passwordIsValid']), function() {

    Route::get('logout', array('as' => 'logout','uses' => 'LoginController@logout'));
    Route::get('/dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));

    Route::group(array('prefix' => 'equipos'), function() {

        Route::get('/', array('as' => 'equipos.index', 'uses' => 'EquiposController@index'));

        Route::get('/{sub}/tipo/{id}', array('as' => 'equipos.tipo', 'uses' => 'EquiposController@tipo'));

        Route::get('/search/{sub}/{id}', array('as' => 'equipos.search', 'uses' => 'EquiposController@search'));

        Route::get('/detail/{id}', array('as' => 'equipos.detail', 'uses' => 'EquiposController@detail'));

        Route::get('/create_daily_check/{id}', array('as' => 'equipos.create_daily_check', 'uses' => 'EquiposController@createDailyCheck'));

        Route::post('/store_daily_check', array('as' => 'equipos.store_daily_check', 'uses' => 'EquiposController@storeDailyCheck'));

        Route::get('/create_mant_prev/{id}/tipo/{tipo}', array('as' => 'equipos.create_mant_prev', 'uses' => 'EquiposController@createMantPrev'));

        Route::post('/store_mant_prev', array('as' => 'equipos.store_mant_prev', 'uses' => 'EquiposController@storeMantPrev'));

        Route::get('/tecnical_support/{id}', array('as' => 'equipos.create_tecnical_support', 'uses' => 'EquiposController@createTecnicalSupport'));

    });

    Route::group(array('prefix' => 'baterias'), function() {
        Route::get('/', array('as' => 'baterias.index', 'uses' => 'BateriaController@index'));

        Route::get('/datatable/{id}', array('as' => 'baterias.datatable', 'uses' => 'BateriaController@datatable'));

        Route::get('/page', array('as' => 'baterias.page', 'uses' => 'BateriaController@page'));

        Route::get('/search', array('as' => 'baterias.search', 'uses' => 'BateriaController@search'));

        Route::get('/{id}', array('as' => 'baterias.detail', 'uses' => 'BateriaController@detail'));

        Route::get('/{id}/registrar_entrada_salida', array('as' => 'baterias.registrar_entrada_salida', 'uses' => 'BateriaController@registrarEntradaSalida'));

        Route::post('/guardar_entrada_salida', array('as' => 'baterias.guardar_entrada_salida', 'uses' => 'BateriaController@guardarEntredaSalida'));
    });

    Route::group(array('prefix' => 'exportar'), function() {

        Route::get('mantenimiento_preventivo/{formulario_registro_id}', array('as' => 'exportar.mantenimiento_preventivo', 'uses' => 'PdfController@exportarMantPrev'));
    });

    Route::group(array('prefix' => 'usuarios'), function() {

        Route::get('/', array('as' => 'usuarios.index', 'uses' => 'UserController@index'));

        Route::get('/create', array('as' => 'usuarios.create', 'uses' => 'UserController@create'));

        Route::get('/search', array('as' => 'usuarios.search', 'uses' => 'UserController@search'));

        Route::get('/import', array('as' => 'usuarios.import', 'uses' => 'UserController@import'));

        Route::get('/{id}', array('as' => 'usuarios.detail', 'uses' => 'UserController@detail'));

        Route::get('/{id}/profile', array('as' => 'usuarios.profile', 'uses' => 'UserController@profile'));

        Route::post('/store', array('as' => 'usuarios.store', 'uses' => 'UserController@store'));

        Route::put('/{id}/edit', array('as' => 'usuarios.update', 'uses' => 'UserController@update'));

        Route::put('/{id}/photo', array('as' => 'usuarios.update_photo', 'uses' => 'UserController@updatePhoto'));


    });

});



