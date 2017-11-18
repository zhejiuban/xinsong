<?php

namespace App\Services;
trait ResponseJsonMessageService
{
    static $status = 'success'; //success成功error失败
    static $message = '';
    static $data = [];
    static $url = '';

    public static function setStatus($status)
    {
        self::$status = $status;
    }

    public static function getStatus()
    {
        return self::$status;
    }

    public static function setMessage($message = '')
    {
        self::$message = $message;
    }

    public static function getMessage()
    {
        return self::$message;
    }

    public static function setData($data = [])
    {
        self::$data = $data;
    }

    public static function getData()
    {
        return self::$data;
    }

    public static function setUrl($url = '')
    {
        self::$url = $url;
    }

    public static function getUrl()
    {
        return self::$url;
    }

    public static function errorResult($message = '', $data = [], $url = '')
    {
        self::setStatus(0);
        self::setData($data);
        self::setMessage($message);
        self::setUrl($url);
        return self::getMessageResult();
    }

    public static function successResult($message = '', $data = [], $url = '')
    {
        self::setStatus(1);
        self::setData($data);
        self::setMessage($message);
        self::setUrl($url);
        return self::getMessageResult();
    }

    public static function getMessageResult()
    {
        return [
            'message' => self::getMessage(),
            'status' => self::getStatus(),
            'data' => self::getData(),
            'url' => self::getUrl(),
        ];
    }
}