<?php

use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;


// Accueil
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ColocationController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/Colocation', [ColocationController::class, 'list'])->name('Colocation');

    Route::get('/Colocation/details/{id}', [ColocationController::class, 'show'])->name('colocation.show');

    Route::get('/createColocation', [ColocationController::class, 'create'])->name('colocation.create');
    Route::post('/Colocation', [ColocationController::class, 'store'])->name('colocation.store');

    Route::post('/colocation/{colocation}/invite', [ColocationController::class, 'invite'])->name('colocation.invite');
    Route::get('/invitation/accept/{token}', [ColocationController::class, 'acceptInvitation'])->name('invitation.accept');
    Route::get('/invitation/refuse/{token}', [ColocationController::class, 'refuseInvitation'])->name('invitation.refuse');
    
    Route::delete('/colocation/{colocation}', [ColocationController::class, 'destroy'])->name('colocation.destroy');
    Route::post('/colocation/{colocation}/leave', [ColocationController::class, 'leave'])->name('colocation.leave');

    Route::post('/colocation/{colocation}/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

});

require __DIR__ . '/auth.php';