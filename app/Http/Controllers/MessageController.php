<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $messages = $user->receivedMessages()
            ->with(['sender', 'business'])
            ->latest()
            ->paginate(15);

        return view('messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        $this->authorize('view', $message);

        /** @var User $user */
        $user = Auth::user();

        if ($message->recipient_id === $user->id) {
            $message->markAsRead();
        }

        $message->load(['sender', 'recipient', 'business']);

        return view('messages.show', compact('message'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => ['nullable', 'exists:businesses,id'],
            'recipient_id' => ['required', 'exists:users,id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        Message::create([
            ...$validated,
            'sender_id' => $user->id,
        ]);

        return back()->with('success', 'Message sent.');
    }

    public function sendToBusiness(Request $request, Business $business)
    {
        $validated = $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        Message::create([
            'business_id' => $business->id,
            'sender_id' => $user->id,
            'recipient_id' => $business->user_id,
            'subject' => $validated['subject'] ?? null,
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Message sent to provider.');
    }
}
