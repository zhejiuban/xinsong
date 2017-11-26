<?php

Route::get('/', function () {
    return redirect('/home');
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
    Route::resource('users', 'UserController');//用户管理

});
/*系统模块*/
Route::group(['prefix' => 'system', 'middleware' => 'auth'], function () {
    Route::post('menus/sync', 'MenuController@sync')->name('menus.sync');
    Route::resource('menus', 'MenuController');
    Route::get('logs','LogController@index')->name('logs.index');
});

Route::group(['prefix' => 'project', 'middleware' => 'auth'], function () {
    Route::resource('projects', 'ProjectController');
    Route::resource('devices', 'DeviceController');
});

Route::group(['prefix' => 'plugin', 'middleware' => 'auth'], function () {
    Route::get('users/selector/data','Plugin\UserSelectorController@data')->name('users.selector.data');
    Route::get('users/selector','Plugin\UserSelectorController@index')->name('users.selector');
    Route::get('projects/selector/data','Plugin\ProjectSelectorController@data')->name('projects.selector.data');
    Route::get('projects/selector','Plugin\ProjectSelectorController@index')->name('projects.selector');

    Route::post('image/upload', 'Plugin\FileController@imageUpload')->name('image.upload');
    Route::post('file/upload', 'Plugin\FileController@fileUpload')->name('file.upload');
    Route::post('video/upload', 'Plugin\FileController@videoUpload')->name('video.upload');
});

Route::group(['prefix' => 'question', 'middleware' => 'auth'], function () {
    Route::get('create',"QuestionController@personalCreate")->name('question.personal.create'); //个人新增协作
    Route::get('personal',"QuestionController@personal")->name('question.personal'); //个人已发问题
    Route::get('pending',"QuestionController@pending")->name('question.pending'); //待处理问题
    Route::match(['get', 'post'],'reply','QuestionController@reply')->name('questions.reply');//问题回复
    Route::post('finished','QuestionController@finished')->name('questions.finished');//问题关闭设置
    Route::delete('questions/delete', 'QuestionController@destroy')->name('questions.delete');
    Route::resource('questions', 'QuestionController');
    Route::resource('categories', 'QuestionCategoryController');
});
