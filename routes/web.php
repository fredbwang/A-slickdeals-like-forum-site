<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| About
|--------------------------------------------------------------------------
| 
| Author: Borui
| Time: July 2018
| Heavily referencing Jeffery Way's work, and special thanks for this genius!
|
 */


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

Route::get('/', function () {
    return redirect('/threads');
});


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadController@index')->name('threads');
Route::post('/threads', 'ThreadController@store');
Route::get('/threads/create', 'ThreadController@create');
Route::get('/threads/{channel}', 'ThreadController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
Route::delete('/replies/{reply}', 'ReplyController@destroy');
Route::patch('/replies/{reply}', 'ReplyController@update');

Route::post('/threads/{channel}/{thread}/subscribe', 'SubscriptionController@store');
Route::delete('/threads/{channel}/{thread}/subscribe', 'SubscriptionController@destroy');

Route::delete('/threads/{channel}/{thread}/subscribe', 'SubscriptionController@destroy');

Route::post('/replies/{reply}/vote/{action}', 'VoteController@store');

Route::get('/profiles/{user}', 'ProfileController@show');

Route::get('/profiles/{user}/notifications', 'UserNotificationController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy')->middleware('auth');

Route::get('/register/confirm', 'Auth\RegistrationController@confirm')->name('register.confirm');

Route::get('/api/users', 'Api\UserController@index');
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar');

// Route::middleware(['auth'])->group(function () {
// });

