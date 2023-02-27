<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ListImageController;
use App\Http\Controllers\ShowImageController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', ListImageController::class)->name('images.all');
Route::get('/images/{image}', ShowImageController::class)->name('images.show');
Route::resource('/account/images', ImageController::class)->except('show');

Route::view('/test-blade', 'test');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
