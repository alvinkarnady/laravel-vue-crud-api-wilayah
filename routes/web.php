<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/users/get-regencies/{provinceId}', [UserController::class, 'getRegencies']);
Route::get('/users/get-districts/{regencyId}', [UserController::class, 'getDistricts']);

Route::get('/', function () {
    return Redirect::route('dashboard');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('users', [UserController::class, 'index'])->name('user.index');
    Route::post('users', [UserController::class, 'store'])->name('user.store');
    Route::patch('users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('users/create', [UserController::class, 'create'])->name('user.create');
});

require __DIR__ . '/auth.php';
