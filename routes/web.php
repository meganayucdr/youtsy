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

Route::get('/', function () {
    return view('home');
});
Route::resource('users', 'UserController');
Route::resource('roles', 'RoleController');
Route::resource('role/users', 'Role\UserController', [ 'as' => 'role' ]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('role/users', 'Role\UserController', [ 'as' => 'role' ]);
Route::resource('roles.users', 'Role\UserController');
Route::resource('holland_codes', 'HollandCodeController');
Route::resource('careers', 'CareerController');
Route::resource('options', 'OptionController');
Route::resource('questions', 'QuestionController');
Route::resource('holland_tests', 'HollandTestController');
Route::resource('holland_test_details', 'HollandTestDetailController');
Route::get('/start_test', 'HollandTestController@showTest')->name('holland_tests.start_test');
Route::put('/store_user_test', 'HollandTestController@storeUserTes')->name('holland_tests.store_user_test');
