<?php

namespace App\Http\Controllers;

use App\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sentinel;
use Cartalyst\Sentinel\Roles\RoleInterface;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /grupo
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if(current_user()->id == 1){
            $data = Rol::get();
        }else{
            $data = Rol::where('id','>',1)->get();
        }

        return view('frontend.roles.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     * GET /grupo/create
     *
     * @return Response

    public function create()
    {
        return view('frontend.rols.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /grupo
     *
     * @return Response

    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name'    => 'required|unique:Rols',
            'slug'    => 'required|unique:Rols',
        ]);

        // store
        $permisos =array();

        foreach($request->except("_token","name") as $key => $value)
        {
            $permisos[str_replace("_",".",$key)] = true;
        }

        $rol = (array(
            'name'        => $request->get('name'),
            'slug'        => $request->get('slug'),
            'permissions' => $permisos,
        ));

        $Rol = Sentinel::getRolRepository()->createModel()->fill($rol)->save();

        if($Rol)
        {
            $request->session()->flash('message.success', 'Creado con exito!');
            return redirect(route('Rol.index'));
        }
        else
        {
            $request->session()->flash('message.error', 'Hubo un problema y no se creo el registro!');
            return redirect(route('Rol.create'));
        }

    } */

    /**
     * Display the specified resource.
     * GET /grupo/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $data = Rol::findOrFail($id);

        return view('sentinel.rols.show')->with('data' , $data);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /grupo/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $data = Rol::findOrFail($id);
        $sentryrol = Sentinel::findRoleById($id);

        return view('frontend.roles.edit')->with('data',$data)->with('sentryRol',$sentryrol);
    }

    /**
     * Update the specified resource in storage.
     * PUT /grupo/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id)
    {

        $this->validate($request, [
            'name'        => 'required',
        ]);
        // store
        $rol = Sentinel::findRoleById($id);

        $rol->name = $request->name;
        $rol->slug = Str::slug($request->name);
        $permisos =array();

        // BUSCAR TODOS LOS PERMISOS

        $permisoss =array();
        // BUSCAR TODOS LOS PERMISOS
        foreach(config('permisos.permissions') as $key => $permisos)
        {

            foreach($permisos as $key2 => $value)
            {

                if($request->get(str_replace(".","_",$key2)))
                {
                    $permisoss[$key2] = true;
                }
                else
                {
                    $permisoss[$key2] = false;
                }
            }

        }

        $rol->permissions = ($permisoss);

        if($rol->save())
        {
            $request->session()->flash('message.success', 'Actualizado con exito!');
            return redirect(route('role.edit',array($id)))->with('sentryRol',$rol);
        }
        else
        {
            $request->session()->flash('message.error', 'Hubo un problema y no se actualizo el registro!');
            return redirect(route('role.edit',array($id)))->with('sentryRol',$rol);
        }

    }

    /**
     * Remove the specified resource from storage.
     * DELETE /grupo/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request,$id)
    {
        $request->session()->flash('message.error', 'Funcion no disponible');
        return redirect(route('Rol.index'));
    }

    public function search(Request $request)
    {
 
            $data = Rol::when(current_user()->id<>1,function($q){
                $q->where('id','>',1);
            })
            ->whereRaw("(name like '%".$request->q."%' or tipo like '%".$request->q."%')")
            ->paginate(10);
     
        return view('frontend.roles.page')->with('data',$data);
    }
}
