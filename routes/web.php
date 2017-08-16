<?php

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/show', 'HomeController@show');

//Attendance Passwords
Route::get('/request_password', 'AttendancePasswordsController@show');
Route::post('/request_password', 'AttendancePasswordsController@request');
Route::get('/call', 'AttendancePasswordsController@call');
Route::get('/call_again', 'AttendancePasswordsController@callAgain');
Route::get('/end', 'AttendancePasswordsController@end');
Route::get('/skip', 'AttendancePasswordsController@skip');
Route::get('/teste', 'AttendancePasswordsController@findByStatus');