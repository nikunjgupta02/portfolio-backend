<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage; // Import your Mailable
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Import the Mail Facade

class ContactController extends Controller
{
   public function handleContact(Request $request) 
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'subject' => 'required|string',
        'message' => 'required|string'
    ]);

    try {
        // 1. Notify YOU about the new message
        Mail::to('nikunjguptank00@gmail.com')->send(new ContactMessage($data));


        // 2. Send an Auto-Reply to THEM
        // We can pass a slightly different subject for them
        Mail::to($data['email'])->send(new ContactMessage([
            'name' => $data['name'],
            'email' => 'nikunjguptank00@gmail.com', 
            'subject' => 'Confirmation: I received your message!',
            'message' => "Hi {$data['name']}, thanks for reaching out! This is an automated confirmation to let you know I've received your message regarding '{$data['subject']}'. I'll get back to you as soon as possible."
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Messages sent successfully!'
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}