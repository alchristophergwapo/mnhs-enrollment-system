<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function allNotif(User $user)
    {
        $notif = $user->notifications;
        $allUnOpenedNotif = [];
        foreach ($notif as $n) {
            if ($n->opened_at == null) {
                array_push($allUnOpenedNotif, $n);
            }
        }
        return response(['notifications' => $allUnOpenedNotif]);
    }

    public function allUnreadNotif(User $user)
    {
        $unread = $user->unreadNotifications;
        return response([
            'notifications' => $unread,
        ]);
    }
}
