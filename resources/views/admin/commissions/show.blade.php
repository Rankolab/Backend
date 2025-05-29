@extends("layouts.app")

@section("title", "Commission Details")

@section("content")
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Commission Details: #{{ $commission->id }}</h1>
        <a href="{{ route("admin.commissions.index") }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    @include("partials.flash_messages")

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Commission Information</h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Commission ID</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $commission->id }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Affiliate</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    <a href="{{ route("admin.affiliates.show", $commission->affiliate) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                        {{ $commission->affiliate->user->name ?? "N/A" }}
                    </a>
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">${{ number_format($commission->amount, 2) }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $commission->status === "paid" ? "bg-blue-100 text-blue-800" : 
                           ($commission->status === "approved" ? "bg-green-100 text-green-800" : 
                           ($commission->status === "rejected" ? "bg-red-100 text-red-800" : "bg-yellow-100 text-yellow-800")) }}">
                        {{ ucfirst($commission->status) }}
                    </span>
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Referred User</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $commission->user->name ?? "N/A" }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Associated Payment ID</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $commission->payment_id ?? "N/A" }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created Date</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $commission->created_at->format("M d, Y H:i") }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Paid At</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $commission->paid_at ? $commission->paid_at->format("M d, Y H:i") : "Not Paid" }}</dd>
            </div>
        </dl>
    </div>

    {{-- Add form for updating status (Approve/Reject/Mark Paid) --}}
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Update Commission Status</h2>
        <form action="{{ route("admin.commissions.update", $commission) }}" method="POST">
            @csrf
            @method("PUT")
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Status</label>
                <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                    <option value="pending" {{ $commission->status == "pending" ? "selected" : "" }}>Pending</option>
                    <option value="approved" {{ $commission->status == "approved" ? "selected" : "" }}>Approved</option>
                    <option value="paid" {{ $commission->status == "paid" ? "selected" : "" }}>Paid</option>
                    <option value="rejected" {{ $commission->status == "rejected" ? "selected" : "" }}>Rejected</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Status
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

