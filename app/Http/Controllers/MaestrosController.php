<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sentinel;
use Cartalyst\Sentinel\Roles\RoleInterface;
use App\Cliente;
use App\Equipo;
use Illuminate\Support\Facades\Schema;

class MaestrosController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /grupo
     *
     * @return Response
     */
    public function index(Request $request)
    {
       
        $data='';
        return view('frontend.maestros.index')->with('data',$data);
    }

    public function clientes(Request $request)
    {
        $columns = Schema::getColumnListing('contactos');
        $data=Cliente::get();
        return view('frontend.maestros.clientes.index')->with(compact('data','columns'));
    }

    public function clientes_create()
    {
        return view('frontend.maestros.clientes.create');
    }

    public function clientes_store(Request $request)
    {   
        $cliente=New Cliente();
        $cliente->fill($request->except('_token'));
        $cliente->tipo_contacto=0;
        
        if($cliente->save())
            $request->session()->flash('message.success','Registro creado con éxito');
        else
            $request->session()->flash('message.error','Registro no pudo ser creado');

        return redirect(route('maestros.clientes.index'));
    }

    public function clientes_edit($id)
    {
        $data=Cliente::find($id);
        return view('frontend.maestros.clientes.edit')->with(compact('data'));
    }
    public function clientes_update($id,Request $request)
    {
        $cliente=Cliente::find($id);
        $cliente->fill($request->except('_token'));
        $cliente->tipo_contacto=0;
        
        if($cliente->save())
            $request->session()->flash('message.success','Registro creado con éxito');
        else
            $request->session()->flash('message.error','Registro no pudo ser creado');

        return redirect(route('maestros.clientes.index'));
    }

    //equipos    
    public function equipos(Request $request)
    {
        $columns = Schema::getColumnListing('equipos');
        $data=Equipo::get();

        return view('frontend.maestros.equipos.index')->with(compact('data','columns'));
    }

    public function equipos_create()
    {
        return view('frontend.maestros.equipos.create');
    }

    public function equipos_store(Request $request)
    {   

        $equipo=New Equipo();
        $equipo->fill($request->except('_token'));
        $equipo->usuario_id=current_user()->id;
        
        if($equipo->save())
            $request->session()->flash('message.success','Registro de equipo creado con éxito');
        else
            $request->session()->flash('message.error','Registro de equipo no pudo ser creado');

        return redirect(route('maestros.equipos.index'));
    }

    public function equipos_edit($id)
    {
        $data=Equipo::find($id);
        return view('frontend.maestros.equipos.edit')->with(compact('data'));
    }
    public function equipos_update($id,Request $request)
    {
        $equipo=Equipo::find($id);
        $equipo->fill($request->except('_token'));
        $equipo->usuario_id=current_user()->id;
        
        if($equipo->save())
            $request->session()->flash('message.success','Registro creado con éxito');
        else
            $request->session()->flash('message.error','Registro no pudo ser creado');

        return redirect(route('maestros.equipos.index'));
    }
}