<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Notifications\AdminMessageNotification;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Forbidden');
        }

        $messages = Message::with(['sender','recipient'])->latest()->paginate(25);
        return view('admin.messages.index', compact('messages'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Forbidden');
        }

        $healthStaff = User::where('role', 'health_staff')->orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.messages.create', compact('healthStaff','users'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Forbidden');
        }

        $data = $request->validate([
            'health_staff_ids' => 'nullable|array',
            'health_staff_ids.*' => 'exists:users,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'send_to_all_health_staff' => 'sometimes|boolean',
            'send_to_all_users' => 'sometimes|boolean',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        // Determine recipients
        $recipients = collect();

        if ($request->boolean('send_to_all_health_staff')) {
            $recipients = User::where('role', 'health_staff')->get();
        }

        if ($request->boolean('send_to_all_users')) {
            $recipients = $recipients->merge(User::where('role', 'user')->get());
        }

        // If no broadcasts chosen, collect individual selections
        if (empty($recipients) || $recipients->isEmpty()) {
            $ids = collect();
            if (!empty($data['health_staff_ids'])) {
                $ids = $ids->merge($data['health_staff_ids']);
            }
            if (!empty($data['user_ids'])) {
                $ids = $ids->merge($data['user_ids']);
            }

            $ids = $ids->unique()->values();
            if ($ids->isNotEmpty()) {
                $recipients = User::whereIn('id', $ids->all())->get();
            }
        }

        if ($recipients->isEmpty()) {
            return redirect()->back()->withInput()->withErrors(['recipients' => 'Please select at least one recipient (health staff or users) or choose a broadcast option.']);
        }

        foreach ($recipients as $recipient) {
            // don't send to the admin themself
            if ($recipient->id === $user->id) {
                continue;
            }

            $message = Message::create([
                'sender_id' => $user->id,
                'recipient_id' => $recipient->id,
                'subject' => $data['subject'] ?? null,
                'body' => $data['body'],
            ]);

            $recipient->notify(new AdminMessageNotification($message));
        }

        return redirect()->route('admin.messages.index')->with('success', 'Message(s) sent.');
    }

    public function show(Message $message)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Forbidden');
        }

        $message->load(['sender','recipient']);
        return view('admin.messages.show', compact('message'));
    }
}
