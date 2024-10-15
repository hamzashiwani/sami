<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventTimelineController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\MainQuizController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\FlightController;
use App\Http\Controllers\Admin\TransportController;


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
        Route::post('users/import-csv', [UserController::class, 'import_csv'])->name('import-csv');

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

        Route::get('event-hotel/{id}', [HotelController::class, 'index'])->name('event-hotel.index');
        Route::get('event-hotel/create/{id}', [HotelController::class, 'create'])->name('event-hotel.create');
        Route::post('event-hotel/store/{id}', [HotelController::class, 'store'])->name('event-hotel.store');
        Route::get('event-hotel/edit/{id}', [HotelController::class, 'edit'])->name('event-hotel.edit');
        Route::post('event-hotel/update/{id}', [HotelController::class, 'update'])->name('event-hotel.update');
        Route::put('event-hotel/update/{id}', [HotelController::class, 'update'])->name('event-hotel.update');
        Route::get('event-hotel/destroy/{id}', [HotelController::class, 'destroy'])->name('event-hotel.destroy');
        Route::post('event-hotel/import-csv/{id}', [HotelController::class, 'import_csv'])->name('import-csv');


        Route::get('event-flight/{id}', [FlightController::class, 'index'])->name('event-flight.index');
        Route::get('event-flight/create/{id}', [FlightController::class, 'create'])->name('event-flight.create');
        Route::post('event-flight/store/{id}', [FlightController::class, 'store'])->name('event-flight.store');
        Route::get('event-flight/edit/{id}', [FlightController::class, 'edit'])->name('event-flight.edit');
        Route::post('event-flight/update/{id}', [FlightController::class, 'update'])->name('event-flight.update');
        Route::put('event-flight/update/{id}', [FlightController::class, 'update'])->name('event-flight.update');
        Route::get('event-flight/destroy/{id}', [FlightController::class, 'destroy'])->name('event-flight.destroy');
         Route::post('event-flight/import-csv/{id}', [FlightController::class, 'import_csv'])->name('import-csv');


        Route::get('event-transport/{id}', [TransportController::class, 'index'])->name('event-transport.index');
        Route::get('event-transport/create/{id}', [TransportController::class, 'create'])->name('event-transport.create');
        Route::post('event-transport/store/{id}', [TransportController::class, 'store'])->name('event-transport.store');
        Route::get('event-transport/edit/{id}', [TransportController::class, 'edit'])->name('event-transport.edit');
        Route::post('event-transport/update/{id}', [TransportController::class, 'update'])->name('event-transport.update');
        Route::put('event-transport/update/{id}', [TransportController::class, 'update'])->name('event-transport.update');
        Route::get('event-transport/destroy/{id}', [TransportController::class, 'destroy'])->name('event-transport.destroy');


        Route::post('upload-image', [
            'uses'  => 'IndexController@uploadImage',
            'as'    => 'upload-image-from-editor'
        ]);
    }
);
