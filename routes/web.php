<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $projects= Auth::user()->projects;
    return view('dashboard', compact('projects'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'user.owns.project'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Projects
    Route::get('/newproject', [ProjectController::class, 'showNew'])->name('new.project');
    Route::post('/newproject', [ProjectController::class, 'create'])->name('create.project');
    Route::get('/project/{project}/edit', [ProjectController::class, 'showEdit'])->name('show.edit.project');
    Route::put('/project/{project}/edit', [ProjectController::class, 'edit'])->name('edit.project');
    Route::delete('/dashboard/{project}', [ProjectController::class, 'delete'])->name('delete.project');
    Route::get('/project/{project}', [ProjectController::class, 'show'])->name('show.project');

    //Task
    Route::get('/project/{project}/newtask', [TaskController::class, 'showNew'])->name('new.task');
    Route::post('/project/{project}/newtask', [TaskController::class, 'store'])->name('store.task');
    Route::get('/project/{project}/{task}/edit', [TaskController::class, 'showEdit'])->name('show.edit.task');
    Route::put('/project/{project}/{task}/edit', [TaskController::class, 'edit'])->name('edit.task');
    Route::delete('/project/{project}/{task}', [TaskController::class, 'delete'])->name('delete.task');
    // Route::get('/project/{project}/{task}', [TaskController::class, 'show'])->name('show.task');
});

require __DIR__.'/auth.php';
