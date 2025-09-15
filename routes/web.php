<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DiscountController;




// Language switching route
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale');

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    });


    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::prefix('users')->name('users.')->middleware('permission:view-users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/add', [UserController::class, 'store'])->middleware('permission:user-add')->name('store');
        Route::get('/edit/{id?}', [UserController::class, 'edit'])->middleware('permission:user-update')->name('edit');
        Route::post('/update/{id?}', [UserController::class, 'update'])->middleware('permission:user-update')->name('update');
        Route::delete('/delete/{id?}', [UserController::class, 'destroy'])->middleware('permission:user-delete')->name('destroy');
        Route::post('/status/{id?}', [UserController::class, 'status'])->middleware('permission:user-update')->name('status');
        Route::get('/permissions/{id?}', [UserController::class, 'permissions'])->middleware('permission:user-permission')->name('permissions');
        Route::post('/permissions/{id?}', [UserController::class, 'updatePermissions'])->middleware('permission:user-permission')->name('updatePermissions');
    });

    Route::prefix('roles')->name('roles.')->middleware('permission:view-roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/add', [RoleController::class, 'store'])->middleware('permission:role-add')->name('store');
        Route::get('/edit/{id?}', [RoleController::class, 'edit'])->middleware('permission:role-update')->name('edit');
        Route::post('/update/{id?}', [RoleController::class, 'update'])->middleware('permission:role-update')->name('update');
        Route::delete('/delete/{id?}', [RoleController::class, 'destroy'])->middleware('permission:role-delete')->name('destroy');
        Route::get('/permissions/{id?}', [RoleController::class, 'permissions'])->middleware('permission:role-permission')->name('permissions');
        Route::post('/permissions/{id?}', [RoleController::class, 'updatePermissions'])->middleware('permission:role-permission')->name('updatePermissions');
    });

    Route::prefix('categories')->name('categories.')->middleware('permission:view-categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/add', [CategoryController::class, 'store'])->middleware('permission:category-add')->name('store');
        Route::get('/edit/{id?}', [CategoryController::class, 'edit'])->middleware('permission:category-update')->name('edit');
        Route::post('/update/{id?}', [CategoryController::class, 'update'])->middleware('permission:category-update')->name('update');
        Route::delete('/delete/{id?}', [CategoryController::class, 'destroy'])->middleware('permission:category-delete')->name('destroy');
    });

    Route::prefix('products')->name('products.')->middleware('permission:view-products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/add', [ProductController::class, 'store'])->middleware('permission:product-add')->name('store');
        Route::get('/edit/{id?}', [ProductController::class, 'edit'])->middleware('permission:product-update')->name('edit');
        Route::post('/update/{id?}', [ProductController::class, 'update'])->middleware('permission:product-update')->name('update');
        Route::delete('/delete/{id?}', [ProductController::class, 'destroy'])->middleware('permission:product-delete')->name('destroy');
        Route::delete('/image/{id?}', [ProductController::class, 'deleteImage'])->middleware('permission:product-update')->name('deleteImage');
    });

    Route::prefix('discounts')->name('discounts.')->middleware('permission:view-discounts')->group(function () {
        Route::get('/', [DiscountController::class, 'index'])->name('index');
        Route::post('/add', [DiscountController::class, 'store'])->middleware('permission:discount-add')->name('store');
        Route::get('/edit/{id?}', [DiscountController::class, 'edit'])->middleware('permission:discount-update')->name('edit');
        Route::post('/update/{id?}', [DiscountController::class, 'update'])->middleware('permission:discount-update')->name('update');
        Route::delete('/delete/{id?}', [DiscountController::class, 'destroy'])->middleware('permission:discount-delete')->name('destroy');
    });

});

require __DIR__.'/auth.php';
