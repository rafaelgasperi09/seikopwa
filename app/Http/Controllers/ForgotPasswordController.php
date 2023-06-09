<?php

namespace App\Http\Controllers;

use App\Notifications\PasswordRecovery;
use App\User;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Illuminate\Http\Request;
use Sentinel;

class ForgotPasswordController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
        ]);

        try {
            $u = User::whereEmail($request->get('email'))->first();

            if($u){
                $user = \Sentinel::findById($u->id);

                $reminder = Reminder::create($user);

                if ($user && $reminder) {
                    $when = now()->addMinutes(1);
                    notifica($u,(new PasswordRecovery($reminder))->delay($when));
                    $request->session()->flash('message.success', 'Se envio un correo a ' . $request->get('email') . ' para que complete el proceso de recuperación de contraseña!');

                }

            }else{
                $request->session()->flash('message.error','El correo ' . $request->get('email') . ' no se encuentra registrado como usuario del sistema.');
            }

            return view('frontend.forgot_password');

        }
        catch (\Exception $e)
        {
            $request->session()->flash('message.error', $e->getMessage());
            return view('frontend.forgot_password');
        }
    }

    public function edit($id,$token,Request $request){

        $user = \Sentinel::findById($id);

        if (Reminder::exists($user, $token)) {
            return view('frontend.recovery_password')->with('id',$id)->with('token',$token);
        }
        else {
            //incorrect info was passed
            $request->session()->flash('message.danger', 'Este link de recuperación es incorrecto o ya expirado , intente recuperar su contraseña nuevamente en el enlace ¿Olvido su contraseña?');
            return redirect('/');
        }
    }

    public function update($id, $token,Request $request) {

        $this->validate($request, [
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'required|min:4'
        ]);

        $user = Sentinel::findById($id);
        $reminder = Reminder::exists($user, $token);

        //incorrect info was passed.
        if (!$reminder) {
            $request->session()->flash('message.error', 'Este link de recuperación es incorrecto o ya expiro , intente recuperar su contraseña nuevamente en el enlace ¿Olvido su contraseña?');
        }else{
            Reminder::complete($user, $token, $request->get('password'));
            $request->session()->flash('message.success', 'Contraseña recuperada exitosamente!');
        }

        return view('frontend.login');
    }
}
