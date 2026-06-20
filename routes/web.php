<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelControl\BookController;
use App\Http\Controllers\PanelControl\DashboardController;


// Routing untuk Auth
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register_process'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('signin');
Route::get('/logout', [AuthController::class, 'logout'])->name('signout');

// Routing untuk Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Routing untuk Manajemen Buku
Route::resource('books', BookController::class)->except(['show']);
