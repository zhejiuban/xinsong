<?php

namespace App\Notifications;

use App\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class QuestionReceive extends Notification
{
    use Queueable;
    public $question;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
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
            'title'=>'新问题待回复通知',
            'type'=>'问题反馈',
            'content'=> $this->question->user->name .'向您提出了新的问题('.$this->question->title.')，请及时接收并回复。',
            'from_user_id'=> $this->question->user_id, //发信人id
            'from_user_name'=> $this->question->user->name, //发信人姓名
            'url'=> url('question/pending?mid=8053529e293f7baef9b15cad1fa80eb6'),
            'data'=>$this->question->toArray()
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
