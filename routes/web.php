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

/*
Route::get('/', function () {
    return view('welcome');
});
*/


// Disable routes for register and reset passwords.
//Auth::routes();
 
 
// Route::get('/', 'HomeController@index')->name('home');  // change to the second to do not redirect instead show login form
Route::get('/','Auth\LoginController@showLoginForm')->name('home');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login') ;

//Route::get('/home', 'HomeController@dashboard')->name('dashboard');
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

Route::get('/dashboard4', 'HomeController@dashboard4')->name('dashboard4');

Route::get('/template', function(){
	
	return view('layouts.template2');
	
})->name('template');

// Route to log when an user opens an email...
Route::get('/timg/{emailId}/{hash}','logController@trackingImageLog')->name('trackingImageLog'); 

 