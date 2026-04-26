<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\GoogleController;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('tasks.index');
})->middleware(['auth'])->name('dashboard');

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tasks', TaskController::class);
});

// Admin panel — requires auth + admin role
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/',                         [AdminController::class, 'index'])      ->name('index');
    Route::post('/seed',                    [AdminController::class, 'seed'])       ->name('seed');
    Route::post('/clear-fake',              [AdminController::class, 'clearFake'])  ->name('clearFake');
    Route::post('/users/{user}/toggle-role',[AdminController::class, 'toggleRole'])->name('toggleRole');
});

require __DIR__.'/auth.php';