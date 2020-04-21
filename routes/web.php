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
Route::get('logout', 'Auth\LoginController@logout', function () {
    return abort(404);
});


Route::get('/home', 'HomeController@index')->name('home');
Route::resource('role/users', 'Role\UserController', [ 'as' => 'role' ]);
Route::resource('roles.users', 'Role\UserController');
Route::resource('holland_codes', 'HollandCodeController');
Route::resource('careers', 'CareerController');
Route::resource('options', 'OptionController');
Route::resource('questions', 'QuestionController');
//Route::resource('holland_tests', 'HollandTestController');
//Route::resource('holland_test_details', 'HollandTestDetailController');
Route::get('/start_test', 'HollandTestController@showTest')->name('holland_tests.start_test');
Route::post('/store_user_test', 'HollandTestController@storeUserTest')->name('holland_tests.store_user_test');
Route::resource('user_scores', 'UserScoreController');
Route::resource('user_score_details', 'UserScoreDetailController');
Route::get('/show_result/{id}', 'HollandTestController@showReport')->name('holland_test.show_report');

Route::get('/get_result/{id}', 'HollandTestController@getResult');
Route::get('dashboard', 'DashboardController')->name('dashboard')->middleware('dashboard');
Route::get('/{id}/results', 'HollandTestController@showResultUser')->name('holland_test.results')->middleware('auth');


Route::resource('holland_codes.careers', 'HollandCode\CareerController');
Route::redirect('holland_codes/{holland_code}/careers/create', '/careers/create');

Route::resource('careers.holland_codes', 'Career\HollandCodeController');
Route::redirect('careers/{career}/holland_codes/create', '/holland_codes/create');
