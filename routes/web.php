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

Route::get('/', function(){
    abort(404);
})->name('login');

Route::get('/{shop}/register', 'UserController@ShowRegisterForm')->name('Register');
Route::post('/register', 'UserController@Register');
Route::get('/{shop}/login', 'UserController@ShowLoginForm')->name('Login');
Route::post('/login', 'UserController@Login');
Route::post('/logout', 'UserController@Logout');
Route::get('{shop}/login/{provider}', 'UserController@RedirectToProvider');
Route::get('login/{provider}/callback', 'UserController@HandleProviderCallback');

Route::get('{shop}/task/{provider}', 'HomeController@RedirectToProvider');
Route::get('task/{provider}/callback', 'HomeController@HandleProviderCallback');

Route::get('{shop}/redeme', 'HomeController@RedemePoints');

Route::get('/{shop}/home', 'HomeController@index')->name('home');

Route::get('/{shop}/status/{provider}', 'HomeController@providerStatus');
