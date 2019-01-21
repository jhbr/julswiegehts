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
Route::get('/check', function () {
    return response()->json([
        'message' => 'Physio-api: Server läuft'
    ], 200);
})->name('start');

Route::get('/', function () {
    return response()->redirectTo('http://landing.shellyfish.de');
});

Route::get('/api-documentation', function() {
    return response()->file(public_path('swagger/api.yml'));
});
