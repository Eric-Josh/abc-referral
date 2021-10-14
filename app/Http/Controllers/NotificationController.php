<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class NotificationController extends Controller
{
    public function markasread($notificationId)
    {
        $markNotification  = auth()->user()->unreadNotifications->where('id', $notificationId)->first();

        if($markNotification){
            $markNotification->markAsRead();
        }

        return redirect()->route('dashboard');
    }

    public function markallasread()
    {
        $user = User::find(auth()->user()->id);
        $user->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->route('dashboard');
    }
}
