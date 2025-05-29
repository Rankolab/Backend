<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    
    public function index()
    {
        if (!auth()->user()->hasPermission('view_reports')) {
            abort(403, 'Unauthorized');
        }

    {
        $notifications = Notification::latest()->paginate(30);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return redirect()->back();
    }
}
