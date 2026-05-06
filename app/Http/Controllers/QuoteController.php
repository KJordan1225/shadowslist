<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Quote;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, ServiceRequest $serviceRequest)
    {
        /** @var User $user */
        $user = Auth::user();

        $business = $user->business;

        abort_unless($business, 403);
        abort_unless($serviceRequest->status === 'open', 403);

        $validated = $request->validate([
            'amount' => ['nullable', 'numeric', 'min:0'],
            'message' => ['required', 'string', 'min:10'],
            'estimated_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        $existingQuote = Quote::where('service_request_id', $serviceRequest->id)
            ->where('business_id', $business->id)
            ->exists();

        if ($existingQuote) {
            return back()->withErrors([
                'quote' => 'You have already submitted a quote for this request.',
            ]);
        }

        $serviceRequest->quotes()->create([
            ...$validated,
            'business_id' => $business->id,
            'status' => 'sent',
        ]);

        return back()->with('success', 'Quote submitted.');
    }

    public function accept(Quote $quote)
    {
        $this->authorize('accept', $quote);

        $quote->update([
            'status' => 'accepted',
        ]);

        $quote->serviceRequest->update([
            'status' => 'closed',
        ]);

        Quote::where('service_request_id', $quote->service_request_id)
            ->where('id', '!=', $quote->id)
            ->update([
                'status' => 'rejected',
            ]);

        return back()->with('success', 'Quote accepted.');
    }
}
