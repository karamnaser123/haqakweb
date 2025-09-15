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

    // Product API routes
    Route::get('/categories', [ProductController::class, 'categories']);
    Route::get('/subcategories/{id?}', [ProductController::class, 'subcategories']);
    Route::get('/productbycategory/{id?}', [ProductController::class, 'productbycategory']);
    Route::get('/productdetails/{id?}', [ProductController::class, 'productdetails']);
    Route::get('/stores', [ProductController::class, 'stores']);
    Route::get('/productsbystore/{id?}', [ProductController::class, 'productsbystore']);

    // Cart API routes
    Route::post('/addtocart', [OrderController::class, 'addtocart']);
    Route::get('/cart', [OrderController::class, 'cart']);
    Route::post('/removefromcart', [OrderController::class, 'removefromcart']);
    Route::post('/updatequantity', [OrderController::class, 'updatequantity']);
    Route::post('/removeallfromcart', [OrderController::class, 'removeallfromcart']);

    // Governorate and City API routes
    Route::get('/getgovernorates', [OrderController::class, 'getgovernorates']);
    Route::get('/getcitiesbygovernorate/{id?}', [OrderController::class, 'getcitiesbygovernorate']);

    // Discount API routes
    Route::post('/apply-discount', [OrderController::class, 'applyDiscount']);
    Route::post('/remove-discount', [OrderController::class, 'removeDiscount']);
    Route::get('/available-discounts', [OrderController::class, 'getAvailableDiscounts']);

    // Checkout API routes
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/myorders', [OrderController::class, 'myorders']);
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
