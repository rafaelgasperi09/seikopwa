<?php

namespace App\Http\Controllers;

use App\Rol;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sentinel;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(){

        $data = User::get();
        return view('frontend.usuarios.index',compact('data'));

    }

    public function search(Request $request){

        $data=User::where('first_name','like',"%".$request->q."%")->orWhere('last_name','like',"%".$request->q."%")->paginate(10);


        return view('frontend.equipos.page')->with('data',$data);
    }

    public function detail($id){

        $data = User::findOrFail($id);
        return view('frontend.usuarios.detail',compact('data'));

    }

    public function profile($id){

        $data = User::findOrFail($id);
        $roles = Rol::pluck('name','id');
        return view('frontend.usuarios.profile',compact('data','roles'));
    }

    public function create(){

        $roles = Rol::pluck('name','id');

        return view('frontend.usuarios.create',compact('roles'));

    }

    public function store(Request $request){

        $this->validate($request, [
            'rol_id'    => 'required',
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'password'         => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        //dd($request->all());

        $user = Sentinel::registerAndActivate(array(
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'password' => $request->get('password'),
        ));

        $role = Sentinel::findRoleById($request->get('rol_id'));
        $role->users()->attach($user);
        $user->crm_user_id = $request->crm_user_id;
        $user->save();
        //$user->notify(new NewUser($user));

        session()->flash('message.success', 'Usuario creado con exito.');
        return redirect(route('usuarios.detail',$user->id));

    }

    public function update(Request $request,$id){

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => 'required|email|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->fill($request->all());

        if($user->save()){
            session()->flash('message.success', 'Usuario creado con exito.');
        }else{
            session()->flash('message.error', 'Hubo un error y no se pudo modificar.');
        }

        return redirect(route('usuarios.detail',$user->id));

    }

    public function updatePassword(Request $request,$id){

        $this->validate($request, [
            'password'         => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        $user = User::findOrFail($id);
        $user->password = $request->password;

        if($user->save()){
            session()->flash('message.success', 'Cambio de contrasena exitosa.');
        }else{
            session()->flash('message.error', 'Hubo un error y no se pudo modificar.');
        }

        return redirect(route('usuarios.profile',$user->id));

    }

    public function updatePhoto(Request $request,$id){

        $this->validate($request, [
            'file'  => 'required',
        ]);

        $user = User::findOrFail($id);
        $file = $request->file('file');
        $ext =  $file->getClientOriginalExtension();
        $filename = 'user_'.$user->id.'_'.Str::random(6).".".$ext;
        $upload = Storage::disk('public')->putFileAs('profile',$file,$filename);
        if($upload){
            $user->photo = $upload;
            $user->save();
            session()->flash('message.success', 'Foto subida con exito.');
        }else{
            session()->flash('message.error', 'Hubo un error y no se pudo modificar.');
        }

        return redirect(route('usuarios.profile',$user->id));

    }


}
