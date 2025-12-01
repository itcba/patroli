<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\PatrolController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Public home page and public submission endpoint — users can submit without logging in.
Route::get('/', [PatrolController::class, 'index']);
Route::get('/api/patrols', [PatrolController::class, 'list'])->middleware('auth'); // Untuk AJAX Load Data (admin-only enforced in controller)
Route::post('/api/patrols', [PatrolController::class, 'store']); // Untuk AJAX Simpan (public)
Route::delete('/api/patrols/{id}', [PatrolController::class, 'destroy'])->middleware('auth'); // Untuk AJAX Hapus (admin)

// Admin-only edit routes (checked in controller)
Route::get('/patrols/{id}/edit', [PatrolController::class, 'edit'])->middleware('auth');
Route::put('/patrols/{id}', [PatrolController::class, 'update'])->middleware('auth');