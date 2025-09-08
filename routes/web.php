<?php

use App\Controllers\QrisController;
use Helpers\Route;
use Helpers\View;

Route::get('/', function(){
    $title = 'Qris Generator';
    return view('home',['title'=>$title]);
});
Route::post('/generate',[QrisController::class, 'generate']);