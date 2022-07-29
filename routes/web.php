<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/', HomeController::class)->name('dashboard');
    Route::get('/dashboard', HomeController::class);
    Route::get('/project_hours/{projectId}', ProjectController::class)->name('project_hours');
    Route::get('users', UsersController::class)->name('users');
    Route::get('entries', EntryController::class)->name('entries');

});
