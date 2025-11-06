<?php
namespace App\Http\Controllers\HealthStaff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(25);
        return view('health_staff.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $user = auth()->user();
        $n = $user->notifications()->where('id', $id)->firstOrFail();
        $n->markAsRead();
        return back();
    }
}
