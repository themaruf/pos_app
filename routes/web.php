<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('customers/data', [CustomerController::class, 'getData'])->name('customers.data');
    Route::resource('customers', CustomerController::class);
    
    Route::get('products/data', [ProductController::class, 'getData'])->name('products.data');
    Route::resource('products', ProductController::class);
    Route::post('/products/check-code', [ProductController::class, 'checkCode'])->name('products.check-code');
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/{sale}/receipt', [PosController::class, 'receipt'])->name('pos.receipt');
    Route::get('/sales', [App\Http\Controllers\PosController::class, 'salesList'])->name('sales.index');
    Route::get('/sales/{sale}/receipt', [PosController::class, 'receipt'])->name('sales.receipt');
});

require __DIR__.'/auth.php';
