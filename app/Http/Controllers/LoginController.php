<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Sentinel;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request){

        $this->validate($request, [
            'login'   => 'required',
            'password'   => 'required',
        ]);

        try
        {
            // Login credentials
            $credentials = array(
                'login' => $request->input('login'),
                'password' => $request->input('password'),
            );

            // Authenticate the user

            $auth = Sentinel::authenticate($credentials, false);

            if ($auth)
            {
                return redirect(route('dashboard'));
            }
            else
            {
                $request->session()->flash('message.error','Usuario o contraseña incorrecta!');
                return view('frontend.login');
            }

        }
        catch (Exception $e)
        {
            $request->session()->flash('message.error', $e->getMessage());
            return view('frontend.login');
        }
        catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e)
        {
            $request->session()->flash('message.error', 'Se ha producido una actividad sospechosa en su dirección IP y se le ha denegado el acceso durante otros 15 minutos.');
            return view('frontend.login');
        }
        catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e)
        {
            $request->session()->flash('message.error', 'Su cuenta esta inactiva , consulte con el administrador del portal para activarla.');
            return view('frontend.login');
        }
    }

    public function loginByPersistence(Request $request,$code){

        $user = Sentinel::findByPersistenceCode($code);
        $success = false;
        if($user){
            $auth = Sentinel::login($user);
            if ($auth)
            {
               $success = true;
            }
        }

        return response()->json([
            'success'=>$success,
        ]);
    }

    public function logout(){
        Session::flush();
        Sentinel::logout();
        return redirect(route('login'));
    }



}
