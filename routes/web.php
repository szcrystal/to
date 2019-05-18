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

Route::get('/', 'Main\HomeController@index');



//DashBoard ===================================================
Route::get('dashboard', 'DashBoard\HomeController@index');

Route::get('dashboard/login', 'DashBoard\LoginController@index');
Route::post('dashboard/login', 'DashBoard\LoginController@postLogin');

Route::get('dashboard/register', 'DashBoard\HomeController@getRegister');
Route::get('dashboard/register/{id}', 'DashBoard\HomeController@getRegister');
Route::post('dashboard/register', 'DashBoard\HomeController@postRegister');
Route::delete('dashboard/register/{id}', 'DashBoard\HomeController@destroy');
Route::get('dashboard/logout', 'DashBoard\HomeController@getLogout');


//setting
Route::resource('dashboard/settings', 'DashBoard\SettingController');





//Main ==========================================================
//Search
Route::get('search', 'Main\SearchController@index');


//Single
Route::get('post/{id}', 'Main\SingleController@index');
Route::post('post/favgood-script', 'Main\SingleController@postFavGoodScript');



//MyPage
Route::get('/mypage', 'MyPage\MyPageController@index');

Route::resource('mypage/ups', 'MyPage\UpController');

Route::get('/mypage/history', 'MyPage\MyPageController@history');
Route::get('/mypage/history/{id}', 'MyPage\MyPageController@showHistory');

Route::get('/mypage/register', 'MyPage\MyPageController@getRegister');
Route::post('/mypage/register', 'MyPage\MyPageController@postRegister');
Route::post('/mypage/register/end', 'MyPage\MyPageController@registerEnd');

Route::get('/mypage/favorite', 'MyPage\MyPageController@favorite');

Route::get('/mypage/optout', 'MyPage\MyPageController@getOptout');
Route::post('/mypage/optout', 'MyPage\MyPageController@postOptout');

//Route::get('logout', 'Auth\LoginController@getLogout');

Auth::routes();
Route::get('/register', 'MyPage\MyPageController@getRegister');
Route::post('/register', 'MyPage\MyPageController@postRegister');
Route::post('/register/end', 'MyPage\MyPageController@registerEnd');




