<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

        'image' => [
            'driver' => 'local',
            'root' => public_path('uploads/image'),
            'base_path' => 'uploads/image',
            'url' => env('APP_URL') . '/uploads/image',
            'validate' => [
                'size' => 5*1024, //单位kb
                'ext' => ['jpg', 'jpeg','png','gif']
            ],
            'upload_path_format' => date("Ym", time())
                . '/' . date("d", time()),
        ],
        'file' => [
            'driver' => 'local',
            'root' => public_path('uploads/file'),
            'base_path' => 'uploads/file',
            'url' => env('APP_URL') . '/uploads/file',
            'validate' => [
                'size' => 20*1024,//单位kb
                'ext' => [
                    'jpg', 'jpeg','png','gif','doc', 'docx',
                    'zip','rar','tar','gz','7z',
                    'docx','txt','pdf','xls','xlsx'
                ]
            ],
            'upload_path_format' => date("Ym", time())
                . '/' . date("d", time()),
        ],
        'video' => [
            'driver' => 'local',
            'root' => public_path('uploads/video'),
            'base_path' => 'uploads/video',
            'url' => env('APP_URL') . '/uploads/video',
        ],

    ],

];
