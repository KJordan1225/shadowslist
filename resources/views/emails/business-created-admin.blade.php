<h2>New Business Created</h2>

<p><strong>Name:</strong> {{ $business->name }}</p>
<p><strong>Owner:</strong> {{ $business->user->name ?? 'N/A' }}</p>
<p><strong>Email:</strong> {{ $business->email }}</p>
<p><strong>Created At:</strong> {{ $business->created_at }}</p>
