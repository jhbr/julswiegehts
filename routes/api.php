<?php

use Illuminate\Http\Request;

//Make sure, every api route has the api middleware
Route::group([
    'middleware' => 'api',
], function () {

    /**********************************
     ***   AUTH
     ********************************/

    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('login', 'Account\UserController@login');
        Route::post('logout', 'Account\UserController@logout');
        Route::post('refresh', 'Account\UserController@refresh');
        Route::post('me', 'Account\UserController@me');
    });

    /**********************************
     ***   PASSWORD FORGOTTEN
     ********************************/

    Route::group([
        'prefix' => 'password',
    ], function () {
        Route::post('email', 'Account\PasswordForgottenController@sendResetLinkEmail')->name('password.email');
        Route::post('reset', 'Account\PasswordForgottenController@reset')->name('password.update');
        //Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    });

    /**********************************
     ***   USER MANAGEMENT
     ********************************/

    Route::group([
        'prefix' => 'user'
    ], function () {
        Route::post('/', 'Account\UserController@storeNewUser');                //Registers a new therapist into the system
        Route::put('/', 'Account\UserController@updateUserData');               //Updates the logged in User
        Route::delete('/{id}', 'Account\UserController@deleteUserFromDatabase');    //Deletes the logged in User from the database
    });

    /**********************************
     ***   EMAIL VERIFICATION
     ********************************/

    Route::group([
        'prefix' => 'email',
    ], function () {
        Route::post('resend', 'Account\EmailVerificationController@resend')->name('email.resend');
        Route::get('verify', 'Account\EmailVerificationController@verify')->name('email.verify');
    });

});
