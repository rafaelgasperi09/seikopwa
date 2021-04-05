<?php

namespace App\Http\Controllers;

use App\UserAccess;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request){

        try
        {
            // Login credentials
            $credentials = array(
                'login' => $request->input('username'),
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
                return view('login');
            }

        }
        catch (Exception $e)
        {
            $request->session()->flash('message.error', $e->getMessage());
            return view('login');
        }
        catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e)
        {
            $request->session()->flash('message.error', 'Se ha producido una actividad sospechosa en su dirección IP y se le ha denegado el acceso durante otros 15 minutos.');
            return view('login');
        }
        catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e)
        {
            $request->session()->flash('message.error', 'Su cuenta esta inactiva , consulte con el administrador del portal para activarla.');
            return view('login');
        }
    }

}
