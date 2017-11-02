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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*组织权限管理*/
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
//    Route::get('groups/lists','GroupController@getList');
    Route::resource('groups', 'GroupController');//用户组管理
    Route::match(['get', 'post'], 'group_power/{group}', 'GroupController@power')->name('groups.power');
    Route::resource('departments', 'DepartmentController');//用户组管理
});

Route::group(['prefix' => 'system', 'middleware' => 'auth'], function () {
    Route::resource('menus','MenuController');
});
