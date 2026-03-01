<?php

use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/Colocation/{id?}', [ColocationController::class, 'index'])->name('Colocation');

    Route::get('/createColocation', [ColocationController::class, 'create'])->name('colocation.create');
    Route::post('/Colocation', [ColocationController::class, 'store'])->name('colocation.store');

    // Actions
    Route::post('/colocation/{colocation}/invite', [ColocationController::class, 'invite'])->name('colocation.invite');
    Route::get('/invitation/accept/{token}', [ColocationController::class, 'acceptInvitation'])->name('invitation.accept');
    Route::get('/invitation/refuse/{token}', [ColocationController::class, 'refuseInvitation'])->name('invitation.refuse');
    
    Route::delete('/colocation/{colocation}', [ColocationController::class, 'destroy'])->name('colocation.destroy');
    Route::post('/colocation/{colocation}/leave', [ColocationController::class, 'leave'])->name('colocation.leave');

    Route::post('/colocation/{colocation}/expenses', [ExpenseController::class, 'store'])
    ->name('expenses.store')
    ->middleware('auth');
});

require __DIR__ . '/auth.php';
