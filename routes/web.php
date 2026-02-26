<?php

use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/createColocation', [ColocationController::class, 'create'])->name('colocation.create');
    Route::post('/Colocation', [ColocationController::class, 'store'])->name('colocation.store');
});

Route::get('/Colocation', function () {
    return view('Colocation');
})->name('Colocation');



require __DIR__ . '/auth.php';
