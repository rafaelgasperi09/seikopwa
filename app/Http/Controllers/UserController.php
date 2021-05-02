<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\MontacargaUser;
use App\Notifications\NewUser;
use App\Rol;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sentinel;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(){

        $data = User::paginate(10);
        return view('frontend.usuarios.index',compact('data'));

    }

    public function search(Request $request){

        $data=User::where('first_name','like',"%".$request->q."%")->orWhere('last_name','like',"%".$request->q."%")
            ->orWhereHas('roles',function ($q) use($request){
                $q->where('name','like',"%".$request->q."%");
            })
            ->paginate(10);


        return view('frontend.usuarios.page')->with('data',$data);
    }

    public function detail($id){

        $data = User::findOrFail($id);
        return view('frontend.usuarios.detail',compact('data'));

    }

    public function updatePasswordView($id){

        $data = User::findOrFail($id);
        return view('frontend.usuarios.update_password',compact('data'));
    }

    public function profile($id){

        $data = User::findOrFail($id);
        $roles = Rol::pluck('name','id');
        return view('frontend.usuarios.profile',compact('data','roles'));
    }

    public function create(){

        $roles = Rol::where('id','<>',1)->pluck('name','id');
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

        $user = Sentinel::registerAndActivate(array(
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'password' => $request->get('password'),
        ));

        $role = Sentinel::findRoleById($request->get('rol_id'));
        $role->users()->attach($user);
        $user->crm_user_id = $request->crm_user_id;
        $user->have_to_change_password = 1;
        // si el el rol es cliente buscarlo en la tabla de contactos del crm para sacar su id
        if($role->slug =='clientes'){
            $cliente = Cliente::where('correo','like',"'".$request->email."'")->first();
            if($cliente) $user->crm_cliente_id = $cliente->id;
        }else{ // buscar si esta en la tabla de usuarios
            // si el el rol es cliente buscarlo en la tabla de contactos del crm para sacar su id
            $crmuser = MontacargaUser::whereEmail($request->email)->first();
            if($crmuser) $user->crm_user_id = $crmuser->id;
        }

        if($user->save()){
            $u = User::find($user->id);
            $u->notify(new NewUser($u,$request->password));
            session()->flash('message.success', 'Usuario creado con éxito. Se ha enviado un correo con los datos de acceso.');

        }else{

            session()->flash('message.success', 'Hubo un error y no se pudo crear.');
        }


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
            session()->flash('message.success', 'Usuario creado con éxito.');
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
        $user->have_to_change_password = 0;
        $user->date_last_password_changed = date('Y-m-d');

        if($user->save()){
            session()->flash('message.success', 'Cambio de contraseña éxitoso.');
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
            session()->flash('message.success', 'Foto subida con éxito.');
        }else{
            session()->flash('message.error', 'Hubo un error y no se pudo modificar.');
        }

        return redirect(route('usuarios.profile',$user->id));

    }


    public function import($id){

        $data = User::findOrFail($id);
        return view('frontend.usuarios.detail',compact('data'));

    }

}
