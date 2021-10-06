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
Route::post('insert', 'Karyawan@create');
Route::get('get', 'Karyawan@read');
Route::post('update', 'Karyawan@update');
Route::get('delete/{id}', 'Karyawan@delete');
