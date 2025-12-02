<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PatrolController;
use App\Http\Controllers\AuthController;

//
// AUTH ROUTES
//
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

//
// PUBLIC ROUTES (Tidak perlu login untuk input patroli)
//
Route::get('/', [PatrolController::class, 'index']);
Route::post('/api/patrols', [PatrolController::class, 'store']); // Submit data patrol (public)


//
// ADMIN ONLY ROUTES
//
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/api/patrols', [PatrolController::class, 'list']);
    Route::get('/patrols/{id}/edit', [PatrolController::class, 'edit']);
    Route::put('/patrols/{id}', [PatrolController::class, 'update']);
    Route::delete('/api/patrols/{id}', [PatrolController::class, 'destroy']);

});