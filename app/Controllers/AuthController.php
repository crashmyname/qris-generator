<?php

namespace App\Controllers;

use App\Models\User;
use Helpers\Auth;
use Helpers\BaseController;
use Bpjs\Core\Request;
use Helpers\Hash;
use Helpers\Response;
use Helpers\Validator;
use Helpers\View;
use Helpers\CSRFToken;

class AuthController extends BaseController
{
    // Controller logic here
    public function login(Request $request)
    {
        $credentials = [
            'identifier' => $request->username,
            'password' => $request->password
        ];
        if(Auth::attempt($credentials)){
            if(Request::isAjax()){       
                return Response::json(['status'=>200,'message'=>'Berhasil login']);
            }
        }
        if(Request::isAjax()){
            return Response::json(['status'=>401, 'message'=>'Username atau password salah']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url: 'login');
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'username' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required'
        ]);
        if($validate){
            return Response::json([
                'status' => 422,
                'message' => $validate
            ]);
        }
        $user = User::create([
            'nama' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);
        if($user){
            return Response::json([
                'status' => 201,
                'message' => 'success register'
            ]);
        }
    }
}
