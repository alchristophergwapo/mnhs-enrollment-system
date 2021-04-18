<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentEnrollEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $student;
    public $notifiable;
    public $notification;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($student, $notifiable)
    {
        $this->student = $student;
        $this->notifiable = $notifiable;
        $this->notification = $notifiable->notifications()->latest()->first();
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'event' => 'event triggered',
    //         // 'student_enrolled' => $this->student,
    //         // 'notification' => $this->notifiable->notifications()->latest()->first(),
    //         // 'user' => $this->notifiable,
    //     ];
    // }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return $this->student;
        return new Channel('student-enroll', $this->student, $this->notifiable, $this->notification);
    }

    public function broadcastAs()
    {
        return 'new-enrollment';
    }
}
