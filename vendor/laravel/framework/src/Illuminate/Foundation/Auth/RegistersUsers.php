<?php

namespace Illuminate\Foundation\Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if($request->input('idRol') == 3){
            $customMessages = [
                'nombre.required' => 'Debes introducir tu nombre completo'
            ];
            $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255'
            ], $customMessages);
        }
        else{
            $customMessages = [
                'nombre.required' => 'Debes introducir tu nombre completo',
                'email.required' => 'Debes introducir un email valido',
                //'email.email' => 'Debe introducir un email <i>Pedro@gmail.com</i>',
                'email.unique' => 'Este email ya esta en uso',
                'password.required' => 'Debes introducir una contraseña',
                'password.confirmed' => 'Su contraseña debe coincidir',
                'password_confirmation.required' => 'Debes introducir la confirmacion de la contraseña',
                'password_confirmation.same' => 'Su contraseña debe coincidir',
                'idRol.required' => 'Seleccione uno de los roles'
            ];
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:3|confirmed',
                'password_confirmation' => 'required|min:3|same:password',
                'idRol' => 'required|max:255',
            ], $customMessages);
        }

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        event(new Registered($user = $this->create($request->all())));

        // Para prevenir el login despues de registrar un usuario
        // $this->guard()->login($user);

        return redirect($this->redirectPath())->withSuccess('Usuario Registrado');
    }

    // public function register(Request $request)
    // {
    //     $this->validator($request->all())->validate();

    //     event(new Registered($user = $this->create($request->all())));

    //     $this->guard()->login($user);

    //     return $this->registered($request, $user)
    //         ?: redirect($this->redirectPath());
    // }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
