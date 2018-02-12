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
//        0 => ['title' => '待接收', 'class' => ' m-badge--danger'],
        1 => ['title' => '未解决', 'class' => ' m-badge--danger'],
        2 => ['title' => '已回复', 'class' => ' m-badge--primary'],
        3 => ['title' => '已解决', 'class' => ' m-badge--success'],
    ],
    'status' => [
        0 => ['title' => '禁用', 'class' => ' m-badge--danger'],
        1 => ['title' => '可用', 'class' => ' m-badge--success'],
    ],
    'project_status' => [
        0 => ['title' => '未开始', 'class' => ' m-badge--danger', 'color' => 'danger'],
        1 => ['title' => '进行中', 'class' => ' m-badge--primary', 'color' => 'primary'],
        2 => ['title' => '已完成', 'class' => ' m-badge--success', 'color' => 'success'],
    ],
    'project_phases_status' => [
        0 => ['title' => '未开始', 'class' => ' m-badge--danger', 'color' => 'danger'],
        1 => ['title' => '进行中', 'class' => ' m-badge--primary', 'color' => 'primary'],
        2 => ['title' => '已完成', 'class' => ' m-badge--success', 'color' => 'success'],
    ],
    'task_status' => [
        0 => ['title' => '进行中', 'class' => ' m-badge--primary', 'color' => 'danger'],
        1 => ['title' => '已完成', 'class' => ' m-badge--success', 'color' => 'success'],
    ],
    'plan_finish_status' => [
        0 => ['title' => '否', 'class' => ' m-badge--danger', 'color' => 'danger'],
        1 => ['title' => '是', 'class' => ' m-badge--success', 'color' => 'success'],
    ],
    'project_default_folder' => [
        [
            'name' => '施工资料',
            'children' => [
                ['name' => '技术协议', 'children' => null],
                ['name' => '平面布置图纸', 'children' => null]
            ]
        ],
        [
            'name' => '程序备份',
            'children' => [
                ['name' => '控制台程序备份', 'children' => null],
                ['name' => '小车程序备份', 'children' => null],
                ['name' => 'PLC程序备份', 'children' => null],
                ['name' => 'I/O表备份', 'children' => null],
            ]
        ],
        [
            'name' => '验收资料',
            'children' => [
                ['name' => '到货验收单年', 'children' => null],
                ['name' => '电气图纸', 'children' => null],
                ['name' => '机械图纸', 'children' => null],
                ['name' => '备件清单', 'children' => null],
                ['name' => '培训签到表', 'children' => null],
                ['name' => '陪产记录表', 'children' => null],
            ]
        ],
        [
            'name' => '其他资料',
            'children' => null
        ],
    ],
    'project_default_phase' => [
        '现场装配',
        '现场调试',
        '陪产试机',
        '终验整改',
        '质保售后'
    ],
    'project_plan_temp' => [
        [
            'name'=>'AGV施工计划',
            'list'=>[
                '接货、开箱',
                '货物清点核对',
                '与甲方确定施工图纸方案',
                '充电站安装',
                '控制台安装',
                'AP电台安装',
                '磁条铺设',
                'AGV地图测试',
                '控制台编辑程序，以及更改AGV车体参数',
                'AGV环线测试',
                '夹具试装',
                '设备培训',
            ]
        ],
        [
            'name'=>'激光叉车施工计划',
            'list'=>[
                "接货、开箱",
                "货物清点核对",
                "与甲方确定施工图纸方案",
                "充电站安装",
                "控制台安装",
                "AP电台安装",
                "反光柱安装",
                "叉车地图测试",
                "控制台编辑程序，以及更改AGV车体参数",
                "叉车环线测试",
                "现场物料托盘调试",
                "设备培训",
            ]
        ],
    ]
];