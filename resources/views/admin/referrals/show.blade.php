@extends("layouts.app")

@section("title", "Referral Details")

@section("content")
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Referral Details: #{{ $referral->id }}</h1>
        <a href="{{ route("admin.referrals.index") }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    @include("partials.flash_messages")

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Referral Information</h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Referral ID</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $referral->id }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Affiliate</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    <a href="{{ route("admin.affiliates.show", $referral->affiliate) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                        {{ $referral->affiliate->user->name ?? "N/A" }}
                    </a>
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Referred User</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{-- Link to user profile if available --}}
                    {{ $referral->referredUser->name ?? "N/A" }} ({{ $referral->referredUser->email ?? "N/A" }})
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Referred At</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $referral->created_at->format("M d, Y H:i") }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Converted At</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $referral->converted_at ? $referral->converted_at->format("M d, Y H:i") : "Not Converted" }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection

