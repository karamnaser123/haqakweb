<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware(['auth:sanctum', 'email.verified'])->group(function () {
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);

    Route::get('/categories', [ProductController::class, 'categories']);
    Route::get('/productbycategory/{id?}', [ProductController::class, 'productbycategory']);
    Route::get('/productdetails/{id?}', [ProductController::class, 'productdetails']);

    Route::post('/addtocart', [OrderController::class, 'addtocart']);
    Route::get('/cart', [OrderController::class, 'cart']);
    Route::post('/removefromcart', [OrderController::class, 'removefromcart']);
    Route::post('/updatequantity', [OrderController::class, 'updatequantity']);
    Route::post('/removeallfromcart', [OrderController::class, 'removeallfromcart']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'me']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/verify-otp', [AuthController::class, 'verifyEmail']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);


});


// Route::prefix('users')->name('users.')->middleware('permission:view-users')->group(function () {
//     Route::get('/', [AuthController::class, 'index'])->name('index');
//     Route::post('/add', [AuthController::class, 'store'])->middleware('permission:user-add')->name('store');
// });
