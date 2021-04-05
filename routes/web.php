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



Route::post('login', array('as' => 'login','uses' => 'LoginController@login'));

Route::group(array('middleware' => 'sentinel.auth'), function() {

    Route::get('/dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));

    Route::group(array('prefix' => 'equipos'), function() {
        Route::get('/', array('as' => 'equipos.index', 'uses' => 'EquiposController@tipos'));
        Route::get('/tipo/{id}', array('as' => 'equipos.index', 'uses' => 'EquiposController@index'));
    });

    Route::group(array('prefix' => 'baterias'), function() {
        Route::get('/', array('as' => 'baterias.index', 'uses' => 'BateriaController@index'));
    });

});



