<?php

namespace App\Observers;

use App\Notifications\TaskReceive;
use App\Task;

class TaskObserver
{
    //问题创建时之后给接收者发送消息通知
    public function created(Task $task)
    {
        $task->leaderUser->notify(new TaskReceive($task));
    }
}