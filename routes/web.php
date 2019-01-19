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

Route::get('/', 'HomeController@index');

//Auth
//Route::post('/register', 'Auth\RegisterController@register');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function() {
    return view('test')->with([
        'islands' => \App\Island::all(),
        'usercount' => \App\User::count()
    ]);
});