<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\PracticeController;
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

Route::get('/practice', [PracticeController::class, 'sample']);
Route::get('/practice2', [PracticeController::class, 'sample2']);
Route::get('/practice3', [PracticeController::class, 'sample3']);

Route::get('/getPractice', [PracticeController::class, 'getPractice']);

Route::get('/movies', [MovieController::class, 'index'])->name('movies');
Route::get('/admin/movies', [MovieController::class, 'adminIndex']);

Route::get('/admin/movies/create', [MovieController::class, 'create']);
Route::post('/admin/movies/store', [MovieController::class, 'store']);

Route::get('/admin/movies/{id}/edit', [MovieController::class, 'edit']);
Route::patch('/admin/movies/{id}/update', [MovieController::class, 'update']);

Route::delete('/admin/movies/{id}/destroy', [MovieController::class, 'destroy']);