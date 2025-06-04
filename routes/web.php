<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return view('welcome');
});

//Rutas para el login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::post('/register', [AuthController::class, 'register']);

//Rutas para la tienda
Route::get('/catalogo', [ProductController::class, 'index'])->name('catalogo');
Route::get('/producto/{id}', [ProductController::class, 'show'])->name('producto.show'); //Página más información del producto
