<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

/*组织权限管理*/
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::match(['get', 'post'], 'groups/power/{group}', 'GroupController@power')->name('groups.power');
    Route::resource('groups', 'GroupController');//用户组管理
    Route::match(['get', 'put'], 'departments/sub/{department}/edit'
        , 'DepartmentController@subUpdate')->name('departments.sub.edit');//子部门编辑
    Route::match(['get', 'post'], 'departments/sub_create'
        , 'DepartmentController@subCreate')->name('departments.sub');//子部门新增

    Route::resource('departments', 'DepartmentController');//分部管理
    Route::post('users/power', 'UserController@power')->name('users.power');//用户授权管理
    Route::post('users/edit_pwd', 'UserController@editPwd')->name('users.edit_pwd');//重置密码
    Route::resource('users', 'UserController');//分部管理

});

Route::group(['prefix' => 'system', 'middleware' => 'auth'], function () {
    Route::post('menus/sync', 'MenuController@sync')->name('menus.sync');
    Route::resource('menus', 'MenuController');
});
