<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ResetPasswordController;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

 Route::middleware('auth:sanctum')->group(function () {
     
    Route::POST('/user', [UserController::class, 'user']);
    Route::POST('/logout', [UserController::class, 'logout']);
 
});

 // registration and login 
 Route::POST('/register', [UserController::class, 'registration']);
 Route::POST('/login', [UserController::class,'login']);
 Route::PUT('/updateUser/{id}', [UserController::class,'updateUser']);


// category and color
Route::POST('/category', [CategoryController::class, 'addCategory']);
Route::POST('/color', [ColorController::class, 'addColor']);

Route::POST('/displayColor', [ColorController::class, 'displayColor']);
Route::POST('/displayCategory', [CategoryController::class, 'displayCategory']);

//product 
Route::POST('/addProducts', [ProductController::class, 'addProducts']);
Route::POST('/displayProduct', [ProductController::class, 'displayProduct']);
Route::POST('/displaySpecificProduct/{id}', [ProductController::class, 'displaySpecificProduct']);
Route::POST('/displayCategory/{id}', [ProductController::class, 'displaySpecificCategoryProducts']);

//ordered Products

Route::POST('/addOrders', [OrderController::class,'addOrders']);
Route::POST('/userOrderHistory/{id}', [OrderController::class,'userOrderHistory']);
Route::POST('/displayAllOrder', [OrderController::class,'displayAllOrder']);
Route::POST('/orderDetailForAdmin/{id}', [OrderController::class,'orderDetailForAdmin']);

//Forgot and Reset Password 
Route::POST('/forgotPassword', [ForgotPasswordController::class,'forgotPassword']);

//Reset Password 

Route::POST('/resetPassword', [ResetPasswordController::class, 'resetPassword']);