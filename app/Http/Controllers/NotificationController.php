<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            
            if (isset($notification->data['ordine_id'])) {
                if (auth()->user()->isSales() || auth()->user()->isAdmin()) {
                    return redirect()->route('orders.show', $notification->data['ordine_id']);
                }
            }
        }
        return back();
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
