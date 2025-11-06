<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
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
        $messages = Message::where('recipient_id', $user->id)->with('sender')->latest()->paginate(25);
        if ($user->role === 'health_staff') {
            return view('health_staff.messages.index', compact('messages'));
        }
        return view('user.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        $user = Auth::user();
        if ($message->recipient_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        if (!$message->read_at) {
            $message->read_at = now();
            $message->save();

            // mark matching unread notifications as read
            $user->unreadNotifications()->where('data->message_id', $message->id)->get()->each->markAsRead();
        }

        $message->load('sender');
        if ($user->role === 'health_staff') {
            return view('health_staff.messages.show', compact('message'));
        }
        return view('user.messages.show', compact('message'));
    }
}
