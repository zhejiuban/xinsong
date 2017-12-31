<?php

namespace App\Notifications;

use App\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskReceive extends Notification
{
    use Queueable;
    public $task;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable){
        //存入数据库的数据
        return [
            'title'=>'您有一个新的任务通知',
            'type'=>'任务通知',
            'content'=> $this->task->user->name .'给您分派了新的任务('.$this->task->content.')，请及时处理。',
            'from_user_id'=> $this->task->user_id, //发信人id
            'from_user_name'=> $this->task->user->name, //发信人姓名
            'url'=> url('project/task/personal?mid=8a77a93cf98062ddc53a30a5383c4d88'),
            'data'=>$this->task->toArray()
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    /*public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }*/

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    /*public function toArray($notifiable)
    {
        return [
            //
        ];
    }*/
}
