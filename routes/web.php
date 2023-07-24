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
        return redirect(route('inicio'));
    }

    return view('frontend.login');
});

Route::get('/dashboard', function () {
        return redirect(route('inicio'));  
});

/*************** USERS LOGIN PASSWORD ROUTES **************************************/
Route::get('/login', function () { return view('frontend.login');});
Route::post('login', array('as' => 'login','uses' => 'LoginController@login'));
Route::post('login_persistence/{code}', array('as' => 'login.persistence','uses' => 'LoginController@loginByPersistence'));


Route::get('forgot_password/create', function () { return view('frontend.forgot_password'); });
Route::post('forgot_password/store', array('as' => 'forgot_password.store','uses' => 'ForgotPasswordController@store'));
Route::get('recovery_password/{id}/{token}', array('as' => 'forgot_password.edit', 'uses' => 'ForgotPasswordController@edit'));
Route::put('recovery_password/{id}/{token}', array('as' => 'forgot_password.update', 'uses' => 'ForgotPasswordController@update'));

Route::get('usuarios/{id}/update_password_view', array('as' => 'usuarios.update_password_view', 'uses' => 'UserController@updatePasswordView'))->middleware('sentinel.auth');
Route::put('usuarios/{id}/password', array('as' => 'usuarios.update_password', 'uses' => 'UserController@updatePassword'))->middleware('sentinel.auth');
/************************************************************************************/

Route::group(array('middleware' => ['sentinel.auth','passwordIsValid']), function() {

    Route::get('logout', array('as' => 'logout','uses' => 'LoginController@logout'));
    Route::get('/inicio', array('as' => 'inicio', 'uses' => 'DashboardController@index'));
    Route::get('/dashboard/{id}', array('as' => 'dashboard.gmp', 'uses' => 'DashboardController@grafica'));
    Route::get('/calendar', array('as' => 'equipos.calendar', 'uses' => 'EquiposController@calendar'));

    Route::group(array('prefix' => 'equipos'), function() {

        Route::get('/', array('as' => 'equipos.index', 'uses' => 'EquiposController@index'))->middleware('hasAccess');

        Route::get('/lista', array('as' => 'equipos.lista', 'uses' => 'EquiposController@lista'));

        Route::get('/reportes_list', array('as' => 'equipos.reportes_list', 'uses' => 'EquiposController@reportes_list'));
        
        Route::get('/reportes_datatable', array('as' => 'equipos.reportes_datatable', 'uses' => 'EquiposController@reportes_datatable'));
   
        Route::get('/reportes_export', array('as' => 'equipos.reportes_export', 'uses' => 'EquiposController@reportes_export'));

        Route::get('/{sub}/tipo/{id}', array('as' => 'equipos.tipo', 'uses' => 'EquiposController@tipo'));

        Route::get('/search/{sub}/{id}', array('as' => 'equipos.search', 'uses' => 'EquiposController@search'));

        Route::get('{id}', array('as' => 'equipos.detail', 'uses' => 'EquiposController@detail'))->middleware('hasAccess');

        Route::get('/reportes/{nombre}/{id}', array('as' => 'reporte.detalle', 'uses' => 'EquiposController@reportes'));



        Route::group(array('prefix' => 'daily_check'), function() {

            Route::get('/create/{id}', array('as' => 'equipos.create_daily_check', 'uses' => 'EquiposController@createDailyCheck'))->middleware('hasAccess');

            Route::post('/store', array('as' => 'equipos.store_daily_check', 'uses' => 'EquiposController@storeDailyCheck'));

            Route::get('/{id}/edit', array('as' => 'equipos.edit_daily_check', 'uses' => 'EquiposController@editDailyCheck'))->middleware('hasAccess');
            
            Route::get('/{id}/show', array('as' => 'equipos.show_daily_check', 'uses' => 'EquiposController@showDailyCheck'));

            Route::put('/{id}/update', array('as' => 'equipos.update_daily_check', 'uses' => 'EquiposController@updateDailyCheck'));

            Route::get('/{id}/delete', array('as' => 'equipos.delete_daily_check', 'uses' => 'EquiposController@deleteRegistroForm'));
        });

        Route::group(array('prefix' => 'mantenimiento_preventivo'), function() {

            Route::get('/create/{id}/tipo/{tipo}', array('as' => 'equipos.create_mant_prev', 'uses' => 'EquiposController@createMantPrev'))->middleware('hasAccess');
              
            Route::get('{id}/show', array('as' => 'equipos.show_mant_prev', 'uses' => 'EquiposController@showMantPrev'));
            
            Route::post('/store', array('as' => 'equipos.store_mant_prev', 'uses' => 'EquiposController@storeMantPrev'));

            Route::get('/{id}/edit', array('as' => 'equipos.edit_mant_prev', 'uses' => 'EquiposController@editMantPrev'))->middleware('hasAccess');

            Route::put('/{id}/update', array('as' => 'equipos.update_mant_prev', 'uses' => 'EquiposController@updateMantPrev'));

            Route::get('/{id}/imprimir', array('as' => 'equipos.imprimir_mant_prev', 'uses' => 'EquiposController@imprimirMantPrev'));

            Route::get('/{id}/delete', array('as' => 'equipos.delete_mant_prev', 'uses' => 'EquiposController@deleteRegistroForm'));

        });

        Route::group(array('prefix' => 'tecnical_support'), function() {

            Route::get('/create/{id}', array('as' => 'equipos.create_tecnical_support', 'uses' => 'EquiposController@createTecnicalSupport'))->middleware('hasAccess');
            
            Route::get('{id}/show', array('as' => 'equipos.show_tecnical_support', 'uses' => 'EquiposController@showTecnicalSupport'));

            Route::get('/create/{id}/{dailycheck}', array('as' => 'equipos.create_tecnical_support_prefilled', 'uses' => 'EquiposController@createTecnicalSupport'))->middleware('hasAccess');
            
            Route::get('/{id}/edit', array('as' => 'equipos.edit_tecnical_support', 'uses' => 'EquiposController@editTecnicalSupport'));

            Route::put('/{id}/assign', array('as' => 'equipos.assign_tecnical_support', 'uses' => 'EquiposController@assignTecnicalSupport'))->middleware('hasAccess');
            
            Route::post('/assign_supervisor', array('as' => 'equipos.assign_supervisor', 'uses' => 'EquiposController@assignSupervisorTS'));
            
            Route::post('/aprobar_cotizacion', array('as' => 'equipos.aprobar_cotizacion', 'uses' => 'EquiposController@aprobar_cotizacion'));
  
            Route::post('/marcar_accidente', array('as' => 'equipos.marcar_accidente', 'uses' => 'EquiposController@marcar_accidente'));

            Route::put('/{id}/start', array('as' => 'equipos.start_tecnical_support', 'uses' => 'EquiposController@startTecnicalSupport'))->middleware('hasAccess');

            Route::put('/{id}/update', array('as' => 'equipos.update_tecnical_support', 'uses' => 'EquiposController@updateTecnicalSupport'));
            
            Route::post('/store', array('as' => 'equipos.store_tecnical_support', 'uses' => 'EquiposController@storeTecnicalSupport'));
            
            Route::post('/agregar_status', array('as' => 'equipos.agregar_status', 'uses' => 'EquiposController@agregar_status'));
           
            Route::get('/{id}/delete', array('as' => 'equipos.delete_tecnical_support', 'uses' => 'EquiposController@deleteRegistroForm'));          

        });


    });

    Route::group(array('prefix' => 'baterias'), function() {
        Route::get('/', array('as' => 'baterias.index', 'uses' => 'BateriaController@index'))->middleware('hasAccess');

        Route::get('/datatable/{id}', array('as' => 'baterias.datatable', 'uses' => 'BateriaController@datatable'));
      
        Route::get('/datatable_st/{id}', array('as' => 'baterias.datatable_st', 'uses' => 'BateriaController@datatable_serv_tecnico'));

        Route::get('/page', array('as' => 'baterias.page', 'uses' => 'BateriaController@page'));

        Route::get('/search', array('as' => 'baterias.search', 'uses' => 'BateriaController@search'));

        Route::get('/{id}', array('as' => 'baterias.detail', 'uses' => 'BateriaController@detail'))->middleware('hasAccess');

        Route::get('/{id}/download', array('as' => 'baterias.download', 'uses' => 'BateriaController@download'));

        Route::get('/{id}/register_in_and_out', array('as' => 'baterias.register_in_and_out', 'uses' => 'BateriaController@registrarEntradaSalida'))->middleware('hasAccess');
    
        Route::post('/store_in_and_out', array('as' => 'baterias.store_in_and_out', 'uses' => 'BateriaController@guardarEntredaSalida'));
    
        Route::get('/{id}/servicio_tecnico', array('as' => 'baterias.serv_tec', 'uses' => 'BateriaController@ServicioTecnico'))->middleware('hasAccess');
        
        Route::post('/{id}/servicio_tecnico_store', array('as' => 'baterias.serv_tec_store', 'uses' => 'BateriaController@ServicioTecnicoStore'));
        
        Route::post('/{id}/servicio_tecnico_update', array('as' => 'baterias.serv_tec_update', 'uses' => 'BateriaController@ServicioTecnicoUpdate'));
       
        Route::get('/{id}/servicio_tecnico_show', array('as' => 'baterias.serv_tec_show', 'uses' => 'BateriaController@ServicioTecnicoShow'));
       
        Route::get('/{id}/servicio_tecnico_edit', array('as' => 'baterias.serv_tec_edit', 'uses' => 'BateriaController@ServicioTecnicoEdit'));

        Route::get('/{id}/download_st', array('as' => 'baterias.download_st', 'uses' => 'BateriaController@download_st'));

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

        Route::put('/{id}/edit', array('as' => 'usuarios.update', 'uses' => 'UserController@update'))->middleware('hasAccess');;

        Route::put('/{id}/photo', array('as' => 'usuarios.update_photo', 'uses' => 'UserController@updatePhoto'));

        Route::delete('/{id}', array('as' => 'usuarios.delete', 'uses' => 'UserController@delete'))->middleware('hasAccess');

        Route::get('/{id}/notifica', array('as' => 'usuarios.notifica', 'uses' => 'UserController@notifica'));

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
        /* ELIMINANDO ROLES */
        Route::get('/search', array('as' => 'role.search','uses' => 'RolController@search'));
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


