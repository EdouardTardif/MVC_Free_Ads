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

Route::get('/','IndexController@showindex');

// Route::get('/',function(){ return view('welcome');});

Auth::routes(['verify' => true]);


Route::get('/home', function(){ redirect('/'); });
Route::get('/profile/edit', 'UserController@edit')->name('user.edit');
Route::post('/profile/edit', 'UserController@update')->name('user.update');

Route::get('/delete', 'UserController@block')->name('user.block');


Route::group(['middleware' => ['auth', 'active_user']], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    // ... Any other routes that are accessed only by non-blocked user
});
// Route::get('/home', 'HomeController@index')->name('home');
