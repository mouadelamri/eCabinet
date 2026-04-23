<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function MarkAllAsRead(){
        $Notifications = Notification::all();
        foreach($Notifications as $notification ){
            $notification->update([
               'est_lu' => true
            ]);
        }
        return back()->with('succes' , 'tous les notification sont lu');
    }
}
