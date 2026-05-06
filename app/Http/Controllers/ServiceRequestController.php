<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $requests = $user->serviceRequests()
            ->with(['category', 'quotes.business'])
            ->latest()
            ->paginate(10);

        return view('service-requests.index', compact('requests'));
    }

    public function create()
    {
        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view('service-requests.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:25'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'size:2'],
            'zip' => ['required', 'string', 'max:10'],
            'desired_date' => ['nullable', 'date', 'after_or_equal:today'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        $serviceRequest = $user->serviceRequests()->create([
            ...$validated,
            'status' => 'open',
        ]);

        return redirect()
            ->route('service-requests.show', $serviceRequest)
            ->with('success', 'Service request created.');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $this->authorize('view', $serviceRequest);

        $serviceRequest->load([
            'category',
            'user',
            'quotes.business.primaryPhoto',
        ]);

        return view('service-requests.show', compact('serviceRequest'));
    }

    public function edit(ServiceRequest $serviceRequest)
    {
        $this->authorize('update', $serviceRequest);

        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view('service-requests.edit', compact('serviceRequest', 'categories'));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorize('update', $serviceRequest);

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:25'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'size:2'],
            'zip' => ['required', 'string', 'max:10'],
            'desired_date' => ['nullable', 'date', 'after_or_equal:today'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'status' => ['required', 'in:open,closed,cancelled'],
        ]);

        $serviceRequest->update($validated);

        return redirect()
            ->route('service-requests.show', $serviceRequest)
            ->with('success', 'Service request updated.');
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $this->authorize('delete', $serviceRequest);

        $serviceRequest->delete();

        return redirect()
            ->route('service-requests.index')
            ->with('success', 'Service request deleted.');
    }
}
