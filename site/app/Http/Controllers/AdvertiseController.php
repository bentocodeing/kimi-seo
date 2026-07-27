<?php

namespace App\Http\Controllers;

use App\Models\AdInquiry;
use Illuminate\Http\Request;

class AdvertiseController extends Controller
{
    public function create()
    {
        return view('advertise');
    }

    public function store(Request $request)
    {
        // Honeypot: real users never fill this hidden field.
        if ($request->filled('website')) {
            return redirect()->route('advertise')
                ->with('success', 'Thanks! Your inquiry has been sent.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        AdInquiry::create($validated);

        return redirect()->route('advertise')
            ->with('success', 'Thanks! Your inquiry has been sent. We will get back to you soon.');
    }
}
