<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage; // Import your Mailable
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Import the Mail Facade

class ContactController extends Controller
{
    public function handleContact(Request $request) 
    {
        // 1. Validate the incoming request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);

        try {
            // 2. Send the email to yourself
            // Using your nikunjgupta02@gmail.com address
            Mail::to('nikunjgupta02@gmail.com')->send(new ContactMessage($data));

            // 3. Return a successful JSON response
            return response()->json([
                'status' => 'success',
                'message' => 'Thanks ' . $data['name'] . ', I have received your message!'
            ], 200);

        } catch (\Exception $e) {
            // 4. Handle any SMTP or Mail errors gracefully
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, something went wrong while sending your message.',
                'debug' => $e->getMessage() // You can remove this for production
            ], 500);
        }
    }
}