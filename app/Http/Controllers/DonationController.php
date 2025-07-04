<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Store a newly created donation in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:bkash,nagad,rocket',
            'transaction_id' => 'required|string|max:255',
            'reason' => 'nullable|string|max:255',
        ]);

        Donation::create($validated);

        return redirect()->back()->with('success', 'আপনার দান সফলভাবে গ্রহণ করা হয়েছে!');
    }
}
