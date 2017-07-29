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

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

//Attendance Passwords
Route::get('/request_password', 'AttendancePasswordsController@show');
Route::post('/request_password', 'AttendancePasswordsController@request');
Route::get('/call', 'AttendancePasswordsController@call');
Route::get('/call_again', 'AttendancePasswordsController@callAgain');
Route::get('/end', 'AttendancePasswordsController@end');
Route::get('/kkk', 'AttendancePasswordsController@newEvent');