@extends("layouts.app")

@section("content")
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Manage Payments</h1>
        <!-- Add filter/search options if needed -->
    </div>

    @include("partials.flash_messages")

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subscription</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($payments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $payment->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            @if($payment->user)
                            <a href="{{ route("admin.users.show", $payment->user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">{{ $payment->user->name }}</a>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @switch($payment->status)
                                    @case("succeeded") bg-green-100 text-green-800 @break
                                    @case("pending")
                                    @case("requires_payment_method")
                                    @case("requires_confirmation")
                                    @case("requires_action") bg-yellow-100 text-yellow-800 @break
                                    @case("processing") bg-blue-100 text-blue-800 @break
                                    @case("failed")
                                    @case("canceled") bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch
                            ">
                                {{ ucfirst(str_replace("_", " ", $payment->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            @if($payment->subscription)
                            <a href="{{ route("admin.subscriptions.show", $payment->subscription) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">#{{ $payment->subscription->id }} ({{ $payment->subscription->plan->name ?? "N/A" }})</a>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $payment->created_at->format("M d, Y H:i") }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route("admin.payments.show", $payment) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">View Details</a>
                            <!-- Add refund action if needed -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
