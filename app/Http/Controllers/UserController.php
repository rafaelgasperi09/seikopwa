<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\MontacargaUser;
use App\Notifications\NewUser;
use App\Rol;
use App\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sentinel;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(){

        $data = User::IsActive()->whereHas('roles',function ($q){ $q->where('role_users.role_id','<>',1);})->FilterClientes()->paginate(10);
        return view('frontend.usuarios.index',compact('data'));

    }

    public function search(Request $request){
        $clientes=Cliente::where('nombre','like',"%".$request->q."%")->get()->pluck('id');
        $where='(';
        foreach($clientes as $k=>$c){ 
            if($k==0)
                $where.="crm_clientes_id like '%$c%'";
            else
                $where.=" or crm_clientes_id like '%$c%'";
        }
        $where.=')';
        
        $data=User::join('activations', 'users.id','=','activations.user_id')->where('activations.completed',1)
                    
                    ->where('first_name','like',"%".$request->q."%")
                    ->orWhere('last_name','like',"%".$request->q."%")
                    ->orWhere('email','like',"%".$request->q."%")
                    ->orWhereHas('roles',function ($q) use($request){
                        $q->where('name','like',"%".$request->q."%");
                        $q->where('long_name','like',"%".$request->q."%");
                    })
                    ->when($where<>'()',function($q) use ($where) {
                        $q->whereRaw($where);
                    })
                    ->selectRaw('users.*')
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
        if(current_user()->id==$id or (current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'))){
            $data = User::findOrFail($id);
            $roles = Rol::where('id','<>',1)->get()->pluck('full_name','id');
            $clientes = Cliente::whereHas('equipos')->orderBy('nombre')->get()->pluck('full_name','id');
            return view('frontend.usuarios.profile',compact('data','roles','clientes'));
        }else{
            return response()->view('frontend.noaccess', [], 403);
        }
   
    }

    public function create(){

        $roles = Rol::where('id','<>',1)->select('name','id','tipo')->get();
        $clientes = Cliente::whereHas('equipos')
                ->orderBy('nombre')
                ->get()
                ->pluck('full_name','id');

        $users = MontacargaUser::whereNotIn('id',User::whereNotNull('crm_user_id')->pluck('crm_user_id'))
            ->orderBy('name')
            ->get()
            ->pluck('full_name','id');

        return view('frontend.usuarios.create',compact('roles','clientes','users'));

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
      

        $role = Sentinel::findRoleById($request->get('rol_id'));

        if($role->tipo == 'cliente' && empty($request->crm_cliente_id) && empty($request->crm_clientes_id)){
            session()->flash('message.error', 'Para rol de cliente la selección de la lista de contactos del CRM es requerida.');
            return redirect(route('usuarios.create'));
        }elseif($role->tipo == 'gmp' && empty($request->crm_user_id)){
            session()->flash('message.error', 'Para rol de GMP la selección de la lista de usuarios del CRM es requerida.');
            return redirect(route('usuarios.create'));
        }


        $user = Sentinel::registerAndActivate(array(
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'password' => $request->get('password'),
        ));

        $role->users()->attach($user);
        $user->have_to_change_password = 0;
        $user->date_last_password_changed = Carbon::now();


        if($role->tipo == 'cliente'){
            $clientes=trim($request->crm_clientes_id,',');
            $cliente=$request->crm_cliente_id;
            if($request->filled('crm_cliente_id'))
               { 
                $clientes=$request->crm_cliente_id.','.$request->crm_clientes_id;
                $clientes=trim($clientes,',');
               }
            if($request->filled('crm_clientes_id')){
                $cliente=explode(',',$clientes);
                $cliente=end($cliente);
                $clientes=str_replace(',,',',',$clientes);
               }
            $user->crm_cliente_id = $cliente;
            $user->crm_clientes_id = $clientes;

        }elseif($role->tipo == 'gmp'){
            $user->crm_user_id = $request->crm_user_id;
            if($request->has('crm_cliente_id')) $user->crm_cliente_id = $request->crm_cliente_id;
        }

        if($user->save()){
            $u = User::find($user->id);
            $when = now()->addMinutes(1);
            notifica($u,(new NewUser($u,$request->password))->delay($when));
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
       
        $clientes=trim($request->crm_clientes_id,',');
        $cliente=$request->crm_cliente_id;
        
        if($request->filled('crm_cliente_id'))
           { 
            $clientes=$request->crm_cliente_id.','.$request->crm_clientes_id;
            $clientes=trim($clientes,',');
           }
        if($request->filled('crm_clientes_id')){
            $cliente=explode(',',$clientes);
            $cliente=end($cliente);
            $clientes=str_replace(',,',',',$clientes);
           }
        
        $user->crm_cliente_id = $cliente;
        $user->crm_clientes_id = $clientes;

        if($user->save()){
           
            if($request->has('rol_id'))
                $user->roles()->sync([$request->rol_id]);

            session()->flash('message.success', 'Usuario modificado con éxito.');
        }else{
            session()->flash('message.error', 'Hubo un error y no se pudo modificar.');
        }

        return redirect(route('usuarios.detail',$user->id));

    }

    public function updatePassword(Request $request,$id){
        if(current_user()->id==$id or (current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador'))){
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
        }else{
            return response()->view('frontend.noaccess', [], 403);
        }

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

    public function delete($id){

        $user = Sentinel::findUserById($id);
        Activation::remove($user);
        return redirect(route('usuarios.index'));
    }

    public function notifica($user_id){

            $u = User::find($user_id);
            $when = now()->addMinutes(1);
           
            notifica($u,(new NewUser($u,'test'))->delay($when));
            session()->flash('message.success', 'Usuario creado con éxito. Se ha enviado un correo con los datos de acceso.');

        
    }

}
