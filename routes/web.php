<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>'guest'],function(){
    Route::get("/",[AuthController::class,"index"])->name("login");
    Route::post("authenticate",[AuthController::class,"authenticate"])->name("authenticate");
    Route::get("register",[AuthController::class,"register"])->name("register");
    Route::post("process-register",[AuthController::class,"processRegister"])->name("processRegister");
});

Route::group(['middleware'=>'auth'],function(){
    Route::get("logout",[AuthController::class, "logout"])->name("user.logout");
    Route::get("dashboard",[UserController::class,"index"])->name("user.dashboard");
});

Route::group(['prefix'=>"admin"],function(){
    Route::group(['middleware'=>'admin.guest'],function(){
        Route::get("/",[AdminLoginController::class,"index"])->name("admin.login");
        Route::post("authenticate",[AdminLoginController::class,"authenticate"])->name("admin.authenticate");
    });

    Route::group(['middleware'=>'admin.auth'],function(){
        Route::get("dashboard",[AdminController::class,"index"])->name("admin.dashboard");
        Route::get("logout",[AdminLoginController::class, "logout"])->name("admin.logout");
    });
});
