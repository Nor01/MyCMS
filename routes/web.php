<?php

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

// Mis Routes

//Formularios de Login y Registro
Route::get('/login', 'ConnectController@getLogin')->name('login');
Route::get('/register', 'ConnectController@getRegister')->name('register');

//Enviar Formulario de Login y Registro
Route::post('/register', 'ConnectController@postRegister')->name('register');

