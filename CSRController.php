<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CSRController extends Controller
{
    public function showForm()
    {
        return view('frontend.csr-form');
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'project_details' => 'required|string',
        ]);

        // Save to database later if needed
        // For now just send mail
        Mail::raw("CSR Form Submission:\n\n" . print_r($validated, true), function ($message) use ($validated) {
            $message->to('your@email.com')
                ->subject('New CSR Form Submission from ' . $validated['company_name']);
        });

        return redirect()->back()->with('success', 'Thank you! Your CSR request has been submitted.');
    }
}
