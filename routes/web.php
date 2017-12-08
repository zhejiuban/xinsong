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
    Route::post("start_or_finish/{project}",'ProjectController@startedAndFinished')->name('project.start_or_finish');
    Route::get('projects/{project}/tasks','ProjectController@tasks')->name('project.tasks');
    Route::get('projects/{project}/dynamics','ProjectController@dynamics')->name('project.dynamics');
    Route::get('projects/{project}/questions','ProjectController@questions')->name('project.questions');
    Route::match(['get', 'post'],'projects/{project}/files/create','ProjectController@filesCreate')->name('project.files.create');
    Route::get('projects/{project}/files','ProjectController@files')->name('project.files');
    Route::delete('projects/{project}/files/{file}','ProjectController@filesDestroy')->name('project.files.destroy');

    Route::resource('projects', 'ProjectController');
    Route::resource('devices', 'DeviceController');
});

Route::group(['prefix' => 'plugin', 'middleware' => 'auth'], function () {
    Route::get('users/selector/data','Plugin\UserSelectorController@data')->name('users.selector.data');
    Route::get('users/selector','Plugin\UserSelectorController@index')->name('users.selector');
    Route::get('projects/selector/data','Plugin\ProjectSelectorController@data')->name('projects.selector.data');
    Route::get('projects/selector','Plugin\ProjectSelectorController@index')->name('projects.selector');
    Route::get('projects/logs','Plugin\ProjectLogController@index')->name('projects.logs');

    Route::post('image/upload', 'Plugin\FileController@imageUpload')->name('image.upload');
    Route::post('file/upload', 'Plugin\FileController@fileUpload')->name('file.upload');
    Route::post('video/upload', 'Plugin\FileController@videoUpload')->name('video.upload');

    Route::get('file/download/{file}', 'Plugin\FileController@download')->name('file.download');
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

Route::group(['prefix' => 'task', 'middleware' => 'auth'], function () {
    Route::match(['get', 'post'],'finisk/{task}','TaskController@finish')->name('tasks.finish');
    Route::resource('tasks', 'TaskController');
});
Route::group(['prefix' => 'dynamic', 'middleware' => 'auth'], function () {
    Route::resource('dynamics', 'DynamicController');
});
Route::group(['prefix' => 'plan', 'middleware' => 'auth'], function () {
    Route::resource('plans', 'PlanController');
});