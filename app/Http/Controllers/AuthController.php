<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(){
        return view("auth.login");
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if($validator->passes()){
            if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
                if(Auth::user()->role!="user"){
                    Auth::logout();
                    return redirect()->route("login")->with('error',"Only Users are Authorized!!");
                }
                return redirect()->route('user.dashboard');
            } else {
                return redirect()->route("login")->with('error',"Either email or password is incorrect");
            }
        }else {
            return redirect()->route('login')->withInput()->withErrors($validator);
        }
    }
    public function register() {
        return view('auth.register');
    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed'
        ]);

        if($validator->passes()){
            $user = new User();
            $user->name = "Deepak";
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = "user";
            $user->save();

            return redirect()->route("login")->with('success',"Registration Successfully!");
            
        }else {
            return redirect()->route('register')->withInput()->withErrors($validator);
        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

}
