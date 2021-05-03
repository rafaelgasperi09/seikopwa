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

    if(\Sentinel::check()){
        return redirect(route('dashboard'));
    }

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

        Route::get('/', array('as' => 'equipos.index', 'uses' => 'EquiposController@index'))->middleware('hasAccess');

        Route::get('/{sub}/tipo/{id}', array('as' => 'equipos.tipo', 'uses' => 'EquiposController@tipo'));

        Route::get('/search/{sub}/{id}', array('as' => 'equipos.search', 'uses' => 'EquiposController@search'));

        Route::get('{id}', array('as' => 'equipos.detail', 'uses' => 'EquiposController@detail'))->middleware('hasAccess');

        Route::get('/create_daily_check/{id}', array('as' => 'equipos.create_daily_check', 'uses' => 'EquiposController@createDailyCheck'))->middleware('hasAccess');

        Route::post('/store_daily_check', array('as' => 'equipos.store_daily_check', 'uses' => 'EquiposController@storeDailyCheck'));

        Route::get('/daily_check/{id}/edit', array('as' => 'equipos.edit_daily_check', 'uses' => 'EquiposController@editDailyCheck'))->middleware('hasAccess');

        Route::put('/daily_check/{id}/update', array('as' => 'equipos.update_daily_check', 'uses' => 'EquiposController@updateDailyCheck'));

        Route::get('/create_mant_prev/{id}/tipo/{tipo}', array('as' => 'equipos.create_mant_prev', 'uses' => 'EquiposController@createMantPrev'))->middleware('hasAccess');

        Route::post('/store_mant_prev', array('as' => 'equipos.store_mant_prev', 'uses' => 'EquiposController@storeMantPrev'));

        Route::get('/mant_prev/{id}/edit', array('as' => 'equipos.edit_mant_prev', 'uses' => 'EquiposController@editMantPrev'))->middleware('hasAccess');

        Route::put('/mant_prev/{id}/update', array('as' => 'equipos.update_mant_prev', 'uses' => 'EquiposController@updateMantPrev'));

        Route::get('/tecnical_support/{id}', array('as' => 'equipos.create_tecnical_support', 'uses' => 'EquiposController@createTecnicalSupport'))->middleware('hasAccess');

        Route::get('/tecnical_support/edit/{id}', array('as' => 'equipos.edit_tecnical_support', 'uses' => 'EquiposController@editTecnicalSupport'));

        Route::post('/store_tecnical_support', array('as' => 'equipos.store_tecnical_support', 'uses' => 'EquiposController@storeTecnicalSupport'));

        Route::get('/reportes/{nombre}/{id}', array('as' => 'reporte.detalle', 'uses' => 'EquiposController@reportes'));
    });

    Route::group(array('prefix' => 'baterias'), function() {
        Route::get('/', array('as' => 'baterias.index', 'uses' => 'BateriaController@index'))->middleware('hasAccess');

        Route::get('/datatable/{id}', array('as' => 'baterias.datatable', 'uses' => 'BateriaController@datatable'));

        Route::get('/page', array('as' => 'baterias.page', 'uses' => 'BateriaController@page'));

        Route::get('/search', array('as' => 'baterias.search', 'uses' => 'BateriaController@search'));

        Route::get('/{id}', array('as' => 'baterias.detail', 'uses' => 'BateriaController@detail'))->middleware('hasAccess');

        Route::get('/{id}/download', array('as' => 'baterias.download', 'uses' => 'BateriaController@download'));

        Route::get('/{id}/register_in_and_out', array('as' => 'baterias.register_in_and_out', 'uses' => 'BateriaController@registrarEntradaSalida'))->middleware('hasAccess');

        Route::post('/store_in_and_out', array('as' => 'baterias.store_in_and_out', 'uses' => 'BateriaController@guardarEntredaSalida'));
    });

    Route::group(array('prefix' => 'exportar'), function() {

        Route::get('mantenimiento_preventivo/{formulario_registro_id}', array('as' => 'exportar.mantenimiento_preventivo', 'uses' => 'PdfController@exportarMantPrev'));
    });

    Route::group(array('prefix' => 'usuarios'), function() {

        Route::get('/', array('as' => 'usuarios.index', 'uses' => 'UserController@index'))->middleware('hasAccess');

        Route::get('/create', array('as' => 'usuarios.create', 'uses' => 'UserController@create'));

        Route::get('/search', array('as' => 'usuarios.search', 'uses' => 'UserController@search'));

        Route::get('/import', array('as' => 'usuarios.import', 'uses' => 'UserController@import'))->middleware('hasAccess');

        Route::get('/{id}', array('as' => 'usuarios.detail', 'uses' => 'UserController@detail'))->middleware('hasAccess');

        Route::get('/{id}/profile', array('as' => 'usuarios.profile', 'uses' => 'UserController@profile'))->middleware('hasAccess');

        Route::post('/store', array('as' => 'usuarios.store', 'uses' => 'UserController@store'));

        Route::put('/{id}/edit', array('as' => 'usuarios.update', 'uses' => 'UserController@update'));

        Route::put('/{id}/photo', array('as' => 'usuarios.update_photo', 'uses' => 'UserController@updatePhoto'));


    });

    Route::group(array('prefix' => 'roles'), function() {
        /* LISTA DE ROLES */
        Route::get('/', array('as' => 'role.index','uses' => 'RolController@index'))->middleware('hasAccess');
        /* BUSCAR ROLES EN LA LISTA */
        Route::get('/search', array('as' => 'role.search', 'uses' => 'RolController@search'));
        /* CREACION ROLES */
        Route::get('/create', array('as' => 'role.create','uses' => 'RolController@create'))->middleware('hasAccess');
        /* CREANDO ROLES */
        Route::post('/', array('as' => 'role.store','uses' => 'RolController@store'))->middleware('hasAccess');
        /* DETALLE ROLES */
        Route::get('/{id}', array('as' => 'role.show','uses' => 'RolController@show'))->where('id', '[0-9]+')->middleware('hasAccess');
        /* EDICION ROLES */
        Route::get('/{id}/edit/', array('as' => 'role.edit','uses' => 'RolController@edit'))->where('id', '[0-9]+')->middleware('hasAccess');
        /* EDITANDO ROLES */
        Route::put('/{id}/update', array('as' => 'role.update','uses' => 'RolController@update'))->where('id', '[0-9]+')->middleware('hasAccess');
        /* ELIMINANDO ROLES */
        Route::delete('/{id}', array('as' => 'role.destroy','uses' => 'RolController@destroy'))->where('id', '[0-9]+')->middleware('hasAccess');
    });


    Route::get("/firma", function(){
        return View::make("frontend.partials.firma1");
     });
     Route::post("/firma", function(){
        return View::make("frontend.partials.firma1");
     });
     Route::post('/firma/send', array('as' => 'firma.store', 'uses' => 'EquiposController@firma'));

     Route::post('/push','PushController@store');


});

Route::get('/offline', function () {
    return view('offline');
});


