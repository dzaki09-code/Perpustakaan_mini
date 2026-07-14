<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelControl\BookController;
use App\Http\Controllers\PanelControl\DashboardController;
use App\Http\Controllers\PanelControl\UserController;
use App\Http\Controllers\PanelControl\LoanController;
use Illuminate\Support\Facades\App;

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
        App::setLocale($locale);
        // Also persist locale in a cookie as a fallback when sessions aren't persisted
        return redirect()->back()->withCookie(cookie()->forever('locale', $locale));
    }
    return redirect()->back();
});

Route::get('/', function () {
    return view('panel_control.index');
})->name('landing');
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register_process'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('signin');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('signout');

    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Routing untuk Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Semua user yang login boleh melihat daftar buku.
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    // Admin/petugas mengelola data buku.
    Route::middleware('role:admin')->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::patch('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        Route::get('/books/open-library/search', [BookController::class, 'openLibrarySearch'])->name('books.open_library.search');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update_status');

        // Manajemen peminjaman
        Route::patch('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::patch('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
    });

    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    // Route return accessible for owner or admin (controller enforces permissions)
    Route::patch('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');

    // Peminjaman buku
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/history', [LoanController::class, 'history'])->name('loans.history');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::post('/books/{book}/borrow', [LoanController::class, 'borrow'])->name('loans.borrow');

});
