<?php

namespace App\Observers;

use App\Notifications\QuestionReceive;
use App\Question;

class QuestionObserver
{
    //问题创建时之后给接收者发送消息通知
    public function created(Question $question)
    {
        $question->receiveUser->notify(new QuestionReceive($question));
    }
}