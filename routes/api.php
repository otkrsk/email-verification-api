<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('test', 'API\VerificationController@test');

Route::get('email/verify/{id}', 'API\VerificationController@verify')->name('verificationapi.verify');
Route::get('email/resend', 'API\VerificationController@resend')->name('verificationapi.resend');

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function()
{
    Route::post('details', 'API\UserController@details')->middleware('verified');
}); // will work only when user has verified the email

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
