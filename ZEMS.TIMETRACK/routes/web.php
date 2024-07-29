<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware('auth')->get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('home');

Route::middleware('auth')->resource('projects', \App\Http\Controllers\ProjectController::class);
Route::middleware('auth')->patch('projects/{project}/done', [ProjectController::class, 'done'])->name('projects.done');


Route::middleware('auth')->resource('tasks', \App\Http\Controllers\TaskController::class);
Route::middleware('auth')->patch('tasks/{task}/done', [TaskController::class, 'done'])->name('tasks.done');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
