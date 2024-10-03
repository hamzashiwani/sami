<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventTimelineController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\MainQuizController;


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

Route::get('admin', 'IndexController@index')->name('login'); // for redirection purpose
Route::name('admin.')->group(
    function () {

    	Route::get('/', 'IndexController@index');

        # to show login form
        Route::get('/auth/login', [
            'uses'  => 'Auth\LoginController@showLoginForm',
            'as'    => 'auth.login'
        ]);

        # login form submits to this route
        Route::post('/auth/login', [
            'uses'  => 'Auth\LoginController@login',
            'as'    => 'auth.login'
        ]);

        # logs out admin user
        # it was post method before I recieved MethodNotAllowedHttpException
        Route::any('/auth/logout', [
            'uses'  => 'Auth\LoginController@logout',
            'as'    => 'auth.logout'
        ]);

        # Password reset routes
        Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
        Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

        # shows dashboard
        Route::get('dashboard', [
            'uses'  => 'DashboardController@index',
            'as'    => 'dashboard.index'
        ]);
        Route::get('update-profile', 'AdministratorsController@editProfile')->name('update-profile');
        Route::post('update-status', 'UserController@updateStatus')->name('update-status');

        Route::resource('administrators', 'AdministratorsController');
        Route::resource('site-settings', 'SiteSettingsController');
        Route::resource('pages', 'PagesController');
        Route::resource('media-files', 'MediaFilesController');
        Route::resource('blog', 'BlogController');
        Route::resource('faq', 'FaqController');
        Route::resource('notification', 'NotificationController');
        // Route::resource('quiz', 'QuizController');
        Route::resource('event', 'EventController');
        Route::resource('testimonial', 'TestimonialController');
        Route::resource('contact-us', 'ContactUsController');
        Route::resource('newsletters', 'NewsLettersController');
        Route::resource('users', 'UserController');
        Route::resource('quiz-view', 'QuizViewController');

        Route::get('event-timeline/{id}', [EventTimelineController::class, 'index'])->name('event-timeline.index');
        Route::get('event-timeline/create/{id}', [EventTimelineController::class, 'create'])->name('event-timeline.create');
        Route::post('event-timeline/store/{id}', [EventTimelineController::class, 'store'])->name('event-timeline.store');
        Route::get('event-timeline/edit/{id}', [EventTimelineController::class, 'edit'])->name('event-timeline.edit');
        Route::post('event-timeline/update/{id}', [EventTimelineController::class, 'update'])->name('event-timeline.update');
        Route::put('event-timeline/update/{id}', [EventTimelineController::class, 'update'])->name('event-timeline.update');
        Route::get('event-timeline/destroy/{id}', [EventTimelineController::class, 'destroy'])->name('event-timeline.destroy');


        Route::get('quiz/{id}', [QuizController::class, 'index'])->name('quiz.index');
        Route::get('quiz/create/{id}', [QuizController::class, 'create'])->name('quiz.create');
        Route::post('quiz/store/{id}', [QuizController::class, 'store'])->name('quiz.store');
        Route::get('quiz/edit/{id}', [QuizController::class, 'edit'])->name('quiz.edit');
        Route::post('quiz/update/{id}', [QuizController::class, 'update'])->name('quiz.update');
        Route::put('quiz/update/{id}', [QuizController::class, 'update'])->name('quiz.update');
        Route::get('quiz/destroy/{id}', [QuizController::class, 'destroy'])->name('quiz.destroy');


        Route::get('main-quiz/{id}', [MainQuizController::class, 'index'])->name('main-quiz.index');
        Route::get('main-quiz/create/{id}', [MainQuizController::class, 'create'])->name('main-quiz.create');
        Route::post('main-quiz/store/{id}', [MainQuizController::class, 'store'])->name('main-quiz.store');
        Route::get('main-quiz/edit/{id}', [MainQuizController::class, 'edit'])->name('main-quiz.edit');
        Route::post('main-quiz/update/{id}', [MainQuizController::class, 'update'])->name('main-quiz.update');
        Route::put('main-quiz/update/{id}', [MainQuizController::class, 'update'])->name('main-quiz.update');
        Route::get('main-quiz/destroy/{id}', [MainQuizController::class, 'destroy'])->name('main-quiz.destroy');

    Route::get('event-hotel/{id}', [EventHotelController::class, 'index'])->name('event-hotel.index');
        Route::get('event-hotel/create/{id}', [EventHotelController::class, 'create'])->name('event-hotel.create');
        Route::post('event-hotel/store/{id}', [EventHotelController::class, 'store'])->name('event-hotel.store');
        Route::get('event-hotel/edit/{id}', [EventHotelController::class, 'edit'])->name('event-hotel.edit');
        Route::post('event-hotel/update/{id}', [EventHotelController::class, 'update'])->name('event-hotel.update');
        Route::put('event-hotel/update/{id}', [EventHotelController::class, 'update'])->name('event-hotel.update');
        Route::get('event-hotel/destroy/{id}', [EventHotelController::class, 'destroy'])->name('event-hotel.destroy');


        Route::post('upload-image', [
            'uses'  => 'IndexController@uploadImage',
            'as'    => 'upload-image-from-editor'
        ]);
    }
);
