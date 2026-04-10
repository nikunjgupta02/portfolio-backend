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
        // We only send ONE email to minimize the risk of a 30s timeout.
        // We add a 'replyTo' so you can respond directly from your inbox.
        Mail::to('nikunjguptank00@gmail.com')
            ->send((new ContactMessage($data))->replyTo($data['email'], $data['name']));

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully!'
        ], 200);

    } catch (\Exception $e) {
        // If it still fails, the error message will appear in the JSON response
        return response()->json([
            'status' => 'error', 
            'message' => 'Mail server error: ' . $e->getMessage()
        ], 500);
    }
}
}