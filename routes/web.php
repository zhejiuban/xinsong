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
    Route::match(['get', 'put'], 'profile', 'UserController@profile')->name('user.profile');
    Route::get('users/export', 'Plugin\UserExportController@index')->name('users.export');
    Route::resource('users', 'UserController');//用户管理
});
/*系统模块*/
Route::group(['prefix' => 'system', 'middleware' => 'auth'], function () {
    Route::post('menus/sync', 'MenuController@sync')->name('menus.sync');
    Route::resource('menus', 'MenuController');
    Route::get('logs', 'LogController@index')->name('logs.index');
});

Route::group(['prefix' => 'project', 'middleware' => 'auth'], function () {
    Route::get('projects/{project}/board', 'ProjectController@board')->name('project.board');
    Route::get('projects/{project}/tasks', 'ProjectController@tasks')->name('project.tasks');
    Route::get('projects/{project}/dynamics', 'ProjectController@dynamics')->name('project.dynamics');
    Route::get('projects/{project}/questions', 'ProjectController@questions')->name('project.questions');
    Route::get('projects/{project}/malfunctions', 'ProjectController@malfunctions')->name('project.malfunctions');

    Route::match(['get', 'post'], 'projects/{project}/users/create', 'ProjectController@usersCreate')->name('project.users.create');
    Route::delete('projects/{project}/users/{user}', 'ProjectController@usersDestroy')->name('project.users.destroy');
    Route::get('projects/{project}/users', 'ProjectController@users')->name('project.users');
    Route::match(['get', 'put'], 'projects/{project}/agent_leader', 'ProjectController@changeAgentLeader')->name('project.agent_leader.update');

    Route::match(['get', 'post'], 'projects/{project}/files/create', 'ProjectController@filesCreate')
        ->name('project.files.create');
    Route::get('projects/{project}/files', 'ProjectController@files')->name('project.files');
    Route::delete('projects/{project}/files/{file}', 'ProjectController@filesDestroy')->name('project.files.destroy');
    Route::match(['get', 'put'], 'projects/{project}/files/{file}/move', 'ProjectController@filesMove')
        ->name('project.files.move');

    //文档分类
    Route::match(['get', 'post'], 'projects/{project}/folders/create', 'ProjectController@foldersCreate')
        ->name('project.folders.create');
    Route::delete('projects/{project}/folders/{folder}', 'ProjectController@foldersDestroy')
        ->name('project.folders.destroy');
    Route::match(['get', 'put'], 'projects/{project}/folders/{folder}/edit', 'ProjectController@foldersUpdate')
        ->name('project.folders.update');


    //个人项目
    Route::get('personal', 'ProjectController@personal')->name('project.personal');
    //个人的任务
    Route::get('task/personal', 'TaskController@personal')->name('task.personal');
    //个人的日志
    Route::get('dynamic/personal', 'DynamicController@personal')->name('dynamic.personal');

    Route::resource('projects', 'ProjectController');
    Route::resource('devices', 'DeviceController');
    Route::match(['get', 'put'], 'phases/{phase}', 'ProjectController@phaseUpdate')->name('project.phases.update');

    Route::get('tasks', 'TaskController@index')->name('project.task.tasks');
    Route::get('tasks/{task}/dynamics', 'TaskController@dynamics')->name('task.dynamics');

    Route::delete('{project}/plans/batch_delete', 'PlanController@batchDelete')->name('plans.batch_delete');
    Route::match(['get', 'post'], '{project}/import', 'PlanController@import')->name('plans.import');
    Route::put('single_field_update', 'PlanController@singleFieldUpdate')->name('plans.field.update');
    Route::get('{project}/plans/export', 'Plugin\PlanExportController@index')->name('plans.export');
    Route::resource('{project}/plans', 'PlanController');


    Route::get('dynamics', 'DynamicController@index')->name('project.dynamic.dynamics');
    Route::get('malfunctions', 'MalfunctionController@index')->name('project.malfunction.malfunctions');

    Route::get('dynamic/fill', 'DynamicController@fillIn')->name('project.dynamic.fill');
});

Route::group(['prefix' => 'plugin', 'middleware' => 'auth'], function () {
    Route::get('users/selector/data', 'Plugin\UserSelectorController@data')->name('users.selector.data');
    Route::get('users/selector', 'Plugin\UserSelectorController@index')->name('users.selector');
    Route::get('users/selector/project/user', 'Plugin\UserSelectorController@projectUserData')->name('project.users.selector');
    Route::get('projects/selector/data', 'Plugin\ProjectSelectorController@data')->name('projects.selector.data');
    Route::get('projects/selector', 'Plugin\ProjectSelectorController@index')->name('projects.selector');
    Route::get('projects/logs', 'Plugin\ProjectLogController@index')->name('projects.logs');
    Route::get('projects/selector/project/device', 'Plugin\ProjectSelectorController@devices')->name('project.devices.selector');
    Route::get('projects/selector/project/phase', 'Plugin\ProjectSelectorController@phases')->name('project.phases.selector');
    Route::get('projects/export', 'Plugin\ProjectExportController@index')->name('projects.export');

//    Route::get('tasks/selector/data', 'Plugin\TaskSelectorController@data')->name('tasks.selector.data');

    Route::post('image/upload', 'Plugin\FileController@imageUpload')->name('image.upload');
    Route::post('file/upload', 'Plugin\FileController@fileUpload')->name('file.upload');
    Route::post('video/upload', 'Plugin\FileController@videoUpload')->name('video.upload');

    Route::get('file/download/{file}', 'Plugin\FileController@download')->name('file.download');

    Route::get('dynamic/need/add/count', 'Plugin\DynamicCountController@needAddDynamicCount')->name('dynamic.need.add.count');
    Route::get('dynamic/need/add/user', 'Plugin\DynamicCountController@needAddDynamicUser')->name('dynamic.need.add.user');
    Route::get('user/need/fill/dynamics', 'Plugin\DynamicCountController@userNeedFillDynamics')->name('user.need.fill.dynamics');

});

Route::group(['prefix' => 'question', 'middleware' => 'auth'], function () {
    Route::get('create', "QuestionController@personalCreate")->name('question.personal.create'); //个人新增协作
    Route::get('personal', "QuestionController@personal")->name('question.personal'); //个人已发问题
    Route::get('pending', "QuestionController@pending")->name('question.pending'); //待处理问题
    Route::match(['get', 'post'], 'reply', 'QuestionController@reply')->name('questions.reply');//问题回复
    Route::post('finished', 'QuestionController@finished')->name('questions.finished');//问题关闭设置
    Route::delete('questions/delete', 'QuestionController@destroy')->name('questions.delete');
    Route::get('questions/export', 'Plugin\QuestionExportController@index')->name('questions.export');
    Route::resource('questions', 'QuestionController');
    Route::resource('categories', 'QuestionCategoryController');
});

Route::group(['prefix' => 'task', 'middleware' => 'auth'], function () {
    Route::match(['get', 'post'], 'finisk/{task}', 'TaskController@finish')->name('tasks.finish');
    Route::get('tasks/export', 'Plugin\TaskExportController@index')->name('tasks.export');
    Route::resource('tasks', 'TaskController');
});
Route::group(['prefix' => 'dynamic', 'middleware' => 'auth'], function () {
    Route::get('dynamics/export', 'Plugin\DynamicExportController@index')->name('dynamics.export');
    Route::resource('dynamics', 'DynamicController');
});
Route::group(['prefix' => 'malfunction', 'middleware' => 'auth'], function () {
    Route::get('malfunctions/export', 'Plugin\MalfunctionExportController@index')->name('malfunctions.export');
    Route::resource('malfunctions', 'MalfunctionController');
});
//消息模块
Route::resource('notifications', 'NotificationController', [
    'only' => ['index'], 'middleware' => 'auth']);
Route::get('notifications/{notification}/read', 'NotificationController@read')
    ->middleware('auth')->name('notifications.read');
Route::get('notifications/mark_read', 'NotificationController@markAsRead')
    ->middleware('auth')->name('notifications.mark_read');

Route::group(['prefix' => 'produce', 'middleware' => 'auth'], function () {
    Route::match(['get', 'post'],
        'product_faults/export'
        , 'ProductFaultController@export')
        ->name('product_faults.export');
    Route::resource('fault_causes', 'FaultCauseController');
    Route::resource('product_faults', 'ProductFaultController');
});

Route::group(['prefix' => 'assessment', 'middleware' => 'auth'], function () {
    Route::get('assessments/personal'
        ,'AssessmentController@show')->name('user.assessments.personal');
    Route::get('user/{user}/assessments'
        ,'AssessmentController@detail')->name('user.assessments');
    Route::match(['get', 'post'],
        'assessments/export'
        , 'AssessmentController@export')
        ->name('assessments.export');
    Route::resource('assessments','AssessmentController');
    Route::resource('rules','AssessmentRuleController');
    Route::resource('scores','UserMonthScoreController');
});