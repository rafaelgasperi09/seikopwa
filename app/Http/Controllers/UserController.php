<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Notifications\NewUser;
use App\Notifications\GenericMail;
use App\Rol;
use App\AccessLog;
use App\User;
use App\Credential;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExcel;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\AccessLogsExport;

class UserController extends Controller
{

    public function index(){

        $data=User::leftjoin('activations', 'users.id','=','activations.user_id')
                    ->where('activations.completed',1)
                    ->WhereHas('roles',function ($q){
                        $q->where('role_users.role_id','<>',1);
                        if(current_user()->isCliente()){
                            $q->where('tipo','cliente');
                        }
                    })
                    ->FilterClientes()
                    ->selectRaw('users.*,activations.completed')
                    ->paginate(10);
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
        
        $data=User::leftjoin('activations', 'users.id','=','activations.user_id')
                    ->where('activations.completed',1)
                    ->where('first_name','like',"%".$request->q."%")
                    ->orWhere('last_name','like',"%".$request->q."%")
                    ->orWhere('email','like',"%".$request->q."%")
                    ->WhereHas('roles',function ($q) use($request){
                        $q->where('role_users.role_id','<>',1);
                        $q->where('name','like',"%".$request->q."%");
                        $q->where('long_name','like',"%".$request->q."%");
                        if(current_user()->isCliente()){
                            $q->where('tipo','cliente');
                        }

                    })
                    ->when($where<>'()',function($q) use ($where) {
                        $q->whereRaw($where);
                    })
                    ->FilterClientes()
                    ->selectRaw('users.*,activations.completed')
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
        $user=current_user();
        $roles = Rol::where('id','<>',1)->FilterClientes()->select('name','id','tipo')->get();

        $clientes = Cliente::whereHas('equipos')
                ->when($user->isCliente(),function($q) use($user){
                    $q->whereIn('id',explode(',',$user->crm_clientes_id));
                })
                ->orderBy('nombre')
                ->get()
                ->pluck('full_name','id');

       /* $users = MontacargaUser::whereNotIn('id',User::whereNotNull('crm_user_id')->pluck('crm_user_id'))
            ->orderBy('name')
            ->get()
            ->pluck('full_name','id');*/

        return view('frontend.usuarios.create',compact('roles','clientes'));

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
        }/*elseif($role->tipo == 'gmp' && empty($request->crm_user_id)){
            session()->flash('message.error', 'Para rol de GMP la selección de la lista de usuarios del CRM es requerida.');
            return redirect(route('usuarios.create'));
        }*/


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
            Credential::create(['user_id'=>$user->id,
                                'encrypted_password'=>Crypt::encrypt($request->password)]);
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
                $credencial=Credential::where('user_id',$user->id)->first();
                if($credencial){
                    $credencial->encrypted_password=Crypt::encrypt($request->password);
                    $credencial->save();
                }else{
                    Credential::create(['user_id'=>$user->id,
                    'encrypted_password'=>Crypt::encrypt($request->password)]);
                }
                               
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

    
    public function activar($id){

        $user = Sentinel::findUserById($id);
        $activacion=Activation::exists($user);
        if($activacion)
            Activation::remove($user);
        $activation_new = Activation::create($user);
        Activation::complete($user,$activation_new->code);
        if( $activation_new)
            session()->flash('message.success', 'Usuario activado con éxito. ');
        else
            session()->flash('message.error', 'Usuario no fue activado con éxito. ');
        return redirect(route('usuarios.index'));
    }

    public function notifica($user_id,Request $request){

            $u = User::find($user_id);
            $when = now()->addMinutes(1);

            $subject='test sistema gmpapp';
            $body='Esta es una prueba de envios de correos sistema gmpapp';
            if(isset($request->subject))
                $subject=$request->subject;
            if(isset($request->body))
                $body=$request->body;
            notifica($u,(new GenericMail($subject,$body))->delay($when));
            session()->flash('message.success', 'Usuario creado con éxito. Se ha enviado un correo con los datos de acceso.');

        
    }

    
    public function export(){
        $cu=current_user();
        $clientes=explode(',',$cu->crm_clientes_id);
        $usuarios = User::when($cu->isCliente(),function($q) use($clientes){
                            $q->where(function($q2) use($clientes){
                                foreach ($clientes as $id) {
                                    $q2->orWhereRaw("FIND_IN_SET(?, crm_clientes_id)", [$id]);
                                }
                            });
                        })->get();
        $data['title']="Reportes de daily check ";
        $data['subtitle']='';
        $lista['datos']=true;
        $data['lista']=array();
        $line=0;
        foreach($usuarios as $u){
            $password='';
            $credencial=Credential::where('user_id',$u->id)->orderBy('id','desc')->first();
            if($credencial){
                $password=$credencial->encrypted_password;
                $password=Crypt::decrypt($password);
            }
            $clientes_user=$u->clientes()->pluck('nombre')->toArray();
            $clientes_user=implode(',',$clientes_user);
            $roles_user=$u->roles()->pluck('name')->toArray();
            $roles_user=implode(',',$roles_user);
            array_push($data['lista'],array(
                'line'=>++$line,
                'nombre'=>$u->fullname,
                'correo'=>$u->email,
                'rol'=>$roles_user,
                'password'=>$password,
                'clientes'=>$clientes_user,
            ));
        }
        

        return Excel::download(new GenericExcel($data), 'Listado_usuarios.xlsx');
    }

    
    public function logs_datatable(Request $request){
         
            $cu=current_user();
            $cliente_ids = explode(',', $cu->crm_clientes_id);
             $data= AccessLog::with('user')
                ->when($cu->isCliente(), function ($q) use ($cliente_ids) {
                    $q->whereHas('user', function ($q2) use ($cliente_ids) {
                        $q2->where(function ($subquery) use ($cliente_ids) {
                            foreach ($cliente_ids as $i => $id) {
                                $subquery->orWhereRaw("FIND_IN_SET(?, crm_clientes_id)", [$id]);
                            }
                        });
                    });
                })->get();
            return DataTables::of($data)
            ->addColumn('usuario', function($row) {
            return $row->user->full_name;
            })
            ->addColumn('fecha', function($row) {
            return \Carbon\Carbon::parse($row->created_at)->format('Y-m-d');
            })
            ->addColumn('hora', function($row) {
            return \Carbon\Carbon::parse($row->created_at)->format('H:i:s');
            })
            ->make(true);
    }

    public function logs(Request $request){
          return view('frontend.usuarios.access_log',compact('data'));
    }

    public function logs_csv(Request $request){
         return Excel::download(new AccessLogsExport, 'access_logs.csv', \Maatwebsite\Excel\Excel::CSV);

    }

}
