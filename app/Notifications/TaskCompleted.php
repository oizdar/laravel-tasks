<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Task $task)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return string[]
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->subject(__('notification.task_completed_subject'))
                    ->line(__('notification.task_completed_line', ['title' => $this->task->title]));
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return string[]
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
