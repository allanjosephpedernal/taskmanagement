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

Auth::routes();
Route::get('/', function () { return redirect('login'); });
Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth:web'])->group(function () {
    Route::get('task/data','TaskController@getData')->name('task.data');
    Route::resource('task', TaskController::class);
});
