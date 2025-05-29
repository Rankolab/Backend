@extends("layouts.app")

@section("content")
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Payment Details</h1>
        <div>
            <!-- Add refund button/logic if needed -->
            <a href="{{ route("admin.payments.index") }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Payments
            </a>
        </div>
    </div>

    @include("partials.flash_messages")

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Payment Information
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                Detailed information about the transaction.
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200 dark:sm:divide-gray-700">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $payment->id }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        @if($payment->user)
                        <a href="{{ route("admin.users.show", $payment->user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">{{ $payment->user->name }} ({{ $payment->user->email }})</a>
                        @else
                        N/A
                        @endif
                    </dd>
                </div>
                 <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subscription</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        @if($payment->subscription)
                        <a href="{{ route("admin.subscriptions.show", $payment->subscription) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">#{{ $payment->subscription->id }} ({{ $payment->subscription->plan->name ?? "N/A" }})</a>
                        @else
                        N/A (One-time payment or subscription link missing)
                        @endif
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">${{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
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
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $payment->description ?? "N/A" }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stripe Payment Intent ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $payment->stripe_payment_intent_id }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stripe Invoice ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $payment->stripe_invoice_id ?? "N/A" }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method Details</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        @if($payment->payment_method_details)
                            <pre class="text-xs bg-gray-100 dark:bg-gray-900 p-2 rounded">{{ json_encode($payment->payment_method_details, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                 <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $payment->created_at->format("M d, Y H:i A") }}</dd>
                </div>
                 <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">{{ $payment->updated_at->format("M d, Y H:i A") }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
