<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        // Prefer notifications that have an explicit sender role set to health_staff
        $query = DatabaseNotification::query()->latest();

                // If the notifications table stores sender role in data->from_role or data->sender_role
                // filter by that when available; also consider notifications that include a sender_id
                // that maps to a user with role 'health_staff'. This makes the admin listing show
                // messages from health staff even if older payloads didn't include sender_role.
                $query->where(function($q){
                        $q->where('data->from_role', 'health_staff')
                            ->orWhere('data->sender_role', 'health_staff')
                            ->orWhere('type', 'like', '%HealthStaff%')
                            // JSON_EXTRACT path for sender_id: check if it matches any user id with role 'health_staff'
                            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.sender_id')) IN (select id from users where role = 'health_staff')");
                });

        $notifications = $query->paginate(25);

        return view('admin.notifications', compact('notifications'));
    }

    public function show($id)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $n = DatabaseNotification::findOrFail($id);
        // mark as read for viewing convenience
        if (method_exists($n, 'markAsRead')) {
            $n->markAsRead();
        }

        return view('admin.notifications-show', ['notification' => $n]);
    }

    public function markAsRead($id)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $n = DatabaseNotification::findOrFail($id);
        if (method_exists($n, 'markAsRead')) {
            $n->markAsRead();
        }
        return back();
    }
}
