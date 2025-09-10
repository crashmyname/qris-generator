<?php

namespace App\Controllers;

use App\Models\User;
use Helpers\Auth;
use Helpers\BaseController;
use Bpjs\Core\Request;
use Helpers\Hash;
use Helpers\Response;
use Helpers\Session;
use Helpers\Validator;
use Helpers\View;
use Helpers\CSRFToken;

class AuthController extends BaseController
{
    // Controller logic here
    public function captcha()
    {
        $code = rand(1000, 9999);
        Session::set('captcha',$code);

        header('Content-Type: image/png');
        $image = imagecreate(80, 30);
        $bg = imagecolorallocate($image, 255, 255, 255);
        $text = imagecolorallocate($image, 0, 0, 0);
        imagestring($image, 5, 10, 8, $code, $text);
        imagepng($image);
        imagedestroy($image);
    }

    public function login(Request $request)
    {
        if ($request->captcha != Session::get('captcha')) {
            return Response::json([
                'status' => 500,
                'message' => 'Captcha salah!'
            ]);
        }
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
        if ($request->captcha != Session::get('captcha')) {
            return Response::json([
                'status' => 500,
                'message' => 'Captcha salah!'
            ]);
        }
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
