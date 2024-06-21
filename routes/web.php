<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortlinkController;
use App\Http\Controllers\UserlinkController;

Route::get('/', function () {
    return view('welcome');
});


require __DIR__.'/auth.php';

// User
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [ShortlinkController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin
Route::middleware(['auth', 'verified', 'checkRole:100'])->group(function () {
    Route::get('/managelink', [UserlinkController::class, 'index'])->name('admins.managelink');
    Route::get('/datalink', [UserlinkController::class, 'datalink'])->name('admins.datalink');
    Route::delete('/links/delete', [UserlinkController::class, 'deleteLinks'])->name('delete.links');
});

//Guest
Route::post('/shortlink', [ShortlinkController::class, 'short'])->name('short.url');
Route::get('/{code}', [ShortlinkController::class, 'show'])->name('short.show');
