<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Hash, Auth;
use App\User;


class ConnectController extends Controller
{
    //Definimos que las funciones que se ejecutan aqui deben ser para usuarios no logueados
    //Exceptuamos las funciones en las que queremos que no se ejecute el middleware en este caso getLogout
    public function __construct(){
        $this->middleware('guest')->except(['getLogout']);
    }
    
    public function getLogin(){ 
        return view('connect.login'); 
    }

    //funcion para inicio de sesion
    public function postLogin(Request $request){

        $rules = [
            'email'=>'required|email',
            'password'=>'required|min:8'
        ];

        $messages = [
            'email.required'=>'El correo es requerido.',
            'email.email'=>'El correo debe tener formato de email@dominio.com.',
            'password.required'=>'La contraseña es requerida.',
            'password.min'=>'La contraseña deber ser de al menos 8 caracteres.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()):
            return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert','danger');
        else: 
            if(Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')],
            true)):

                return redirect('/');

            else: 
                return back()->with('message','Correo o Contraseña mal escritos')->with('typealert','danger');

            endif;
        endif;

    }

    public function getRegister(){ 
        return view('connect.register'); 
    }

    //funcion para registro de usuarios
    public function postRegister(Request $request){ 
        //Creamos reglas de validacion para los formularios
        $rules=[
            'name'=>'required',
            'lastname'=> 'required',
            'email'=>'required|email|unique:App\User,email',
            'password'=>'required|min:8',
            'cpassword'=>'required|min:8|same:password'
        ];

        //traducciones de las reglas de validacion
        $messages =[
            'name.required'=>'Su nombre es requerido.',
            'lastname.required'=>'Su apellido es requerido.',
            'email.required'=>'Su correo es requerido.',
            'email.email'=>'El Correo debe ser en formato email@dominio.com.',
            'email.unique'=>'Este correo ya existe en la base de datos.',
            'password.required'=>'La contraseña es requerida.',
            'password.min'=>'La contraseña debe ser de al menos 8 caracteres.',
            'cpassword.required'=>'Es necesario confirmar la contraseña.',
            'cpassword.min'=>'La contraseña debe ser de al menos 8 caracteres.',
            'cpassword.same'=>'Las contraseñas no coinciden.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()):
            return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert','danger');
        else: 
            $user = new User;
            $user->name = e($request->input('name'));
            $user->lastname=e($request->input('lastname'));
            $user->email=e($request->input('email'));
            $user->password= Hash::make($request->input('password'));

            if($user->save()):
                return redirect('/login')->with('message','Su usuario se ha registrado, ahora puede ingresar')->with('typealert','success');

            endif;
        endif;

    }

    //funcion para cerrar sesion
    public function getLogout(){
        Auth::logout();
        return redirect('/');
    }



}