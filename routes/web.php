<?php

use App\Controllers\AuthController;
use App\Controllers\QrisController;
use App\Models\Qris;
use Helpers\AuthMiddleware;
use Helpers\Route;
use Helpers\Session;
use Helpers\View;

Route::get('/', function(){
    return view('auth/login');
});
Route::post('/login',[AuthController::class, 'login']);
Route::get('/login', function(){
    return view('auth/login');
});
Route::get('/register', function(){
    return view('auth/register');
});
Route::post('/register',[AuthController::class, 'register']);
Route::group([AuthMiddleware::class],function(){
    Route::get('/home', function(){
        $title = 'Qris Generator';
        return view('home',['title'=>$title],'layout/app');
    });
    Route::post('/generate',[QrisController::class, 'generate']);
    Route::get('/decoder',function(){
        $title = 'Qris Decoder';
        $qris = Qris::query()->where('userId','=',Session::user()->userId)->first();
        if(!$qris){
            return view('decoder',['title'=>$title,'data'=>'Anda harus upload barcode QRIS terlebih dahulu sebelum bisa generate QRIS.'],'layout/app');
        }
        return view('decoder',['title'=>$title,'data'=>'Merchant Name : '.$qris->merchantname],'layout/app');
    });
    Route::post('/decoder',[QrisController::class, 'decoderQris']);
    Route::get('/logout',[AuthController::class, 'logout']);
});