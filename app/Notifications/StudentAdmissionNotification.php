<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentAdmissionNotification extends Notification
{
    use Queueable;

    public $enrollmentDetails;
    public $additionalDetails;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($enrollmentDetails, $additionalDetails)
    {
        $this->enrollmentDetails = $enrollmentDetails;
        $this->additionalDetails = $additionalDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
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
        $introMessage = 'sad';
        if ($this->enrollmentDetails->enrollment_status === 'Approved') {
            
            $introMessage = 'glad';
        }
        return (new MailMessage)
                    ->subject('Admission Update')
                    ->line('Good day '.$this->enrollmentDetails->student->firstname.'.')
                    ->line('We are '.$introMessage.' to inform that your application for admission has been '.$this->enrollmentDetails->enrollment_status.'.')
                    // ->action('Sign In', url('http://mnhsenrollment-frontend.herokuapp.com/sign-in'))
                    ->line($this->additionalDetails)
                    ->line('Best Regards!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
