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

Route::get('/', 'DiskController@index');
Route::get('/login', 'UserController@index');
Route::post('/loginForm', 'UserController@login');
Route::get('/logout', 'UserController@logOut');


Route::get('/goHome', 'DiskController@goHome');
Route::get('/goShare', 'DiskController@goShare');
Route::get('/share', 'DiskController@share');
Route::get('/myDisk', 'DiskController@myDisk');
Route::get('/shareDisk', 'DiskController@shareDisk');
Route::post('/deleteFile', 'DiskController@deleteFile');
Route::post('/deleteAllFile', 'DiskController@deleteAllFile');
Route::post('/addFolder', 'DiskController@addFolder');
Route::post('/editFolder', 'DiskController@editFolder');

Route::post('/uploadFiles', 'UploadController@uploadFiles');
Route::post('/uploadFolder', 'UploadController@uploadFolder');

