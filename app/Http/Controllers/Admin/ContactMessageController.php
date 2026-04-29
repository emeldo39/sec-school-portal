<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        // Mark all unread as read when admin opens inbox
        ContactMessage::where('is_read', false)->update(['is_read' => true]);

        $messages = ContactMessage::latest()->paginate(30);

        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $subject = $message->subject;
        $message->delete();

        ActivityLog::record('admin_delete_message', "Deleted contact message: {$subject}");

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted.');
    }
}
