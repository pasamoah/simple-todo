<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// TodoContollers
Route::post('/add-todo', [App\Http\Controllers\TodoController::class, 'store']);
Route::get('/get-todo', [App\Http\Controllers\TodoController::class, 'show']);
Route::get('/delete-todo/{todo}', [App\Http\Controllers\TodoController::class, 'destroy']);
Route::get('/edit-todo/{todo}', [App\Http\Controllers\TodoController::class, 'edit']);
Route::post('/update-todo/{todo}', [App\Http\Controllers\TodoController::class, 'update']);

Route::get('/completed-data/{todo}', [App\Http\Controllers\TodoController::class, 'completedData']);

Route::post('/change-completed/{todo}', [App\Http\Controllers\TodoController::class, 'changeCompleted']);

