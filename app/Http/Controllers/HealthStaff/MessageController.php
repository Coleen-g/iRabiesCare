<?php
namespace App\Http\Controllers\HealthStaff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Notifications\AdminMessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'health_staff') {
            abort(403);
        }

        $messages = Message::where('recipient_id', $user->id)->latest()->paginate(25);
        return view('health_staff.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        // ensure the health staff can view messages addressed to them
        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }

        // mark read when viewed by recipient
        if ($message->recipient_id === $user->id && !$message->read_at) {
            $message->read_at = now();
            $message->save();
        }

        return view('health_staff.messages.show', compact('message'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'health_staff') {
            abort(403);
        }

        // list admin and regular user recipients
        $admins = User::where('role', 'admin')->orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('health_staff.messages.create', compact('admins','users'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'health_staff') {
            abort(403);
        }

        $data = $request->validate([
            'admin_ids' => 'nullable|array',
            'admin_ids.*' => 'exists:users,id',
            'send_to_all_admins' => 'sometimes|boolean',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'send_to_all_users' => 'sometimes|boolean',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        $recipients = collect();

        // Broadcasts
        if ($request->boolean('send_to_all_admins')) {
            $recipients = $recipients->merge(User::where('role', 'admin')->get());
        }
        if ($request->boolean('send_to_all_users')) {
            $recipients = $recipients->merge(User::where('role', 'user')->get());
        }

        // Individual selections
        $ids = collect();
        if (!empty($data['admin_ids'])) {
            $ids = $ids->merge($data['admin_ids']);
        }
        if (!empty($data['user_ids'])) {
            $ids = $ids->merge($data['user_ids']);
        }

        $ids = $ids->unique()->values();
        if ($ids->isNotEmpty()) {
            $recipients = $recipients->merge(User::whereIn('id', $ids->all())->get());
        }

        $recipients = $recipients->unique('id');

        if ($recipients->isEmpty()) {
            return redirect()->back()->withInput()->withErrors(['recipients' => 'Please select at least one recipient (admin or user) or choose a broadcast option.']);
        }

        foreach ($recipients as $recipient) {
            if ($recipient->id === $user->id) continue;

            $message = Message::create([
                'sender_id' => $user->id,
                'recipient_id' => $recipient->id,
                'subject' => $data['subject'] ?? null,
                'body' => $data['body'],
            ]);

            // Notify the recipient (admin or user)
            $recipient->notify(new AdminMessageNotification($message));
        }

        return redirect()->route('health_staff.dashboard')->with('success', 'Message(s) sent.');
    }
}
