<?php

use Illuminate\Support\Facades\Route;

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




    Route::get('/dashboard', function () {
        return view('frontend.dashboard');
    });
    Route::get('/equipos', array('as' => 'equipos.index', 'uses' => 'EquiposController@index'));
    Route::get('/equipos/tipo/{$id}', array('as' => 'equipos.index', 'uses' => 'EquiposController@index'));

