<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'agency' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'reason' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        \Illuminate\Support\Facades\Mail::to('hola@viantryp.com')->send(new \App\Mail\ContactFormMail($validated));

        return redirect()->back()->with('success', '¡Gracias por contactarnos! Tu mensaje ha sido enviado exitosamente.');
    }
}
