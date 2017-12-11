<?php
/**
 * Created by PhpStorm.
 * User: Guess
 * Date: 2017/11/14
 * Time: 下午2:04
 */
return [
    'page' => [
        'per_page' => 10
    ],
    'question_status' => [
        0 => ['title' => '待接收', 'class' => ' m-badge--danger'],
        1 => ['title' => '处理中', 'class' => ' m-badge--warning'],
        2 => ['title' => '已回复', 'class' => ' m-badge--primary'],
        3 => ['title' => '已关闭', 'class' => ' m-badge--success'],
    ],
    'status' => [
        0 => ['title' => '禁用', 'class' => ' m-badge--danger'],
        1 => ['title' => '可用', 'class' => ' m-badge--success'],
    ],
    'project_status' => [
        0 => ['title' => '未开始', 'class' => ' m-badge--danger'],
        1 => ['title' => '进行中', 'class' => ' m-badge--primary'],
        2 => ['title' => '已完成', 'class' => ' m-badge--success'],
    ],
    'project_phases_status' => [
        0 => ['title' => '未开始', 'class' => ' m-badge--danger'],
        1 => ['title' => '进行中', 'class' => ' m-badge--primary'],
        2 => ['title' => '已完成', 'class' => ' m-badge--success'],
    ],
    'task_status' => [
        0 => ['title' => '进行中', 'class' => ' m-badge--primary','color'=>'danger'],
        1 => ['title' => '已完成', 'class' => ' m-badge--success','color'=>'success'],
    ],
];