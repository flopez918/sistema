<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;

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
    return view('auth.login');
});

/*Route::get('/empleado', function () {
    return view('empleado.index');
});

Route::get('/empleado/create',[EmpleadoController::class,'create']);*/



Route::resource('empleado', EmpleadoController::class)->middleware('auth'); // Puedo acceder a todas las URLs

Auth::routes();

Route::get('/home', [EmpleadoController::class, 'index'])->name('home');  

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [EmpleadoController::class, 'index'])->name('home');  // Redirecciona
});
