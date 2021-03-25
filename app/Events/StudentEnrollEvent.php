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
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($student, $notifiable)
    {
        $this->student = $student;
        $this->notifiable = $notifiable;
    }

    public function broadcastWith() {
        return [
            'student_enrolled' => $this->student,
            'notification' => $this->notifiable->notifications()->latest()->first(),
            'user' => $this->notifiable,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('student_enroll');
    }
}
