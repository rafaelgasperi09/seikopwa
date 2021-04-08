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

Route::get('/login', function () {
    return view('frontend.login');
});


Route::post('login', array('as' => 'login','uses' => 'LoginController@login'));

Route::group(array('middleware' => 'sentinel.auth'), function() {

    Route::get('logout', array('as' => 'logout','uses' => 'LoginController@logout'));
    Route::get('/dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));

    Route::group(array('prefix' => 'equipos'), function() {

        Route::get('/', array('as' => 'equipos.index', 'uses' => 'EquiposController@index'));

        Route::get('/{sub}/tipo/{id}', array('as' => 'equipos.tipo', 'uses' => 'EquiposController@tipo'));

        Route::get('/search/{sub}/{id}', array('as' => 'equipos.search', 'uses' => 'EquiposController@search'));

        Route::get('/detail/{id}', array('as' => 'equipos.detail', 'uses' => 'EquiposController@detail'));

        Route::get('/create_daily_check/{id}', array('as' => 'equipos.create_daily_check', 'uses' => 'EquiposController@createDailyCheck'));

        Route::post('/store_daily_check', array('as' => 'equipos.store_daily_check', 'uses' => 'EquiposController@storeDailyCheck'));
        
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

});



