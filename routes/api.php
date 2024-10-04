<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('signup', 'ApiController@signup');
Route::post('forget-password', 'ApiController@forgetPassword');
Route::post('reset-password', 'ApiController@resetPassword');
Route::post('login', 'ApiController@login');


Route::group(['prefix' => 'events'], function () {
	Route::get('get-questions', 'EventController@getQuestions');
	Route::get('update-status', 'EventController@updateStatus');
	Route::get('get-quiz', 'EventController@getQuiz');
});
// Authenticated routes
Route::group(['middleware' => ['jwt.verify']], function() {
	Route::post('background-login', 'ApiController@backgroundLogin');
	Route::post('update-profile', 'ApiController@updateProfile');
	Route::post('change-password', 'ApiController@changePassword');
	Route::post('view-profile', 'ApiController@viewProfile');
	Route::post('save-device-token', 'ApiController@saveDeviceToken');
	Route::post('logout', 'ApiController@logout');
});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
	Route::post('register', 'PassportAuthController@register');
	Route::post('login', 'PassportAuthController@login');
	Route::post('verify-otp', 'PassportAuthController@verify_otp');
	Route::post('resend-code', 'PassportAuthController@resend_code');
	Route::post('forgot-password', 'ForgetPasswordController@sendMail');
	Route::post('verify-code', 'ForgetPasswordController@checkCode');
	Route::post('reset-password', 'ForgetPasswordController@resetPassword');
	
});


Route::group(['middleware' => 'auth:sanctum'], function () {
	Route::group(['prefix' => 'notification'], function () {
		Route::get('show', 'NotificationController@index');
		Route::get('detail', 'NotificationController@detail');
		Route::get('mark-all-read', 'NotificationController@markAllAsRead');
	});

	Route::group(['prefix' => 'events'], function () {
		Route::get('show', 'EventController@index');
		Route::get('get-attendance', 'EventController@getAttendance');
		Route::get('detail', 'EventController@detail');
		Route::post('check-in', 'EventController@attendance');
		Route::get('get-quiz', 'EventController@getQuestionsApi');
		Route::post('submit-answer', 'EventController@submitAnswer');
	});

	Route::group(['prefix' => 'dashboard'], function () {
		Route::get('/', 'EventController@dashboard');
	});
});