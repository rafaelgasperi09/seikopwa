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

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('frontend.login');
});

Route::get('/dashboard', function () {
    return view('frontend.dashboard');
});


Route::post('login', array('as' => 'login','uses' => 'LoginController@login'));

Route::group(array('middleware' => 'sentinel.auth'), function() {

    Route::group(array('prefix' => 'equipos'), function() {

        Route::get('/', array('as' => 'equipos.index', 'uses' => 'EquiposController@tipos'));
        Route::get('/tipo/{id}', array('as' => 'equipos.index', 'uses' => 'EquiposController@index'));

    });

});



