<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orWhere('is_global', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.notifications.index', compact('notifications'));
    }
    
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id === Auth::id() || $notification->is_global) {
            $notification->is_read = true;
            $notification->save();
            
            return redirect()->back()->with('success', 'Notification marked as read');
        }
        
        return redirect()->back()->with('error', 'Unauthorized action');
    }
    
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->orWhere('is_global', true)
            ->update(['is_read' => true]);
            
        return redirect()->back()->with('success', 'All notifications marked as read');
    }
    
    public function destroy(Notification $notification)
    {
        if ($notification->user_id === Auth::id() || Auth::user()->isAdmin()) {
            $notification->delete();
            return redirect()->back()->with('success', 'Notification deleted');
        }
        
        return redirect()->back()->with('error', 'Unauthorized action');
    }
}
