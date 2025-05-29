@extends("layouts.app")

@section("content")
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Create New Plan</h1>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route("admin.plans.store") }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Plan Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan Name</label>
                    <input type="text" name="name" id="name" value="{{ old("name") }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    @error("name")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                    <input type="number" name="price" id="price" value="{{ old("price", 0.00) }}" step="0.01" min="0" required class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    @error("price")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Interval -->
                <div>
                    <label for="interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Interval</label>
                    <select name="interval" id="interval" required class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                        <option value="month" {{ old("interval") == "month" ? "selected" : "" }}>Monthly</option>
                        <option value="year" {{ old("interval") == "year" ? "selected" : "" }}>Yearly</option>
                    </select>
                    @error("interval")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stripe Price ID (Optional) -->
                <div>
                    <label for="stripe_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stripe Price ID (Optional)</label>
                    <input type="text" name="stripe_id" id="stripe_id" value="{{ old("stripe_id") }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave blank to potentially auto-generate or if not using Stripe.</p>
                    @error("stripe_id")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">{{ old("description") }}</textarea>
                    @error("description")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Features (Using simple textarea for now, could be improved with dynamic inputs) -->
                <div class="md:col-span-2">
                    <label for="features_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Features (One per line)</label>
                    <textarea name="features_text" id="features_text" rows="5" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">{{ old("features") ? implode("\n", old("features")) : "" }}</textarea>
                    <input type="hidden" name="features" id="features_hidden">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Enter each feature on a new line.</p>
                    @error("features")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Active -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old("is_active", 1) ? "checked" : "" }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Is Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route("admin.plans.index") }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancel</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Plan
                </button>
            </div>
        </form>
    </div>
</div>

@push("scripts")
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const featuresText = document.getElementById("features_text");
    const featuresHidden = document.getElementById("features_hidden");

    form.addEventListener("submit", function() {
        // Convert textarea lines to JSON array for the hidden input
        const featuresArray = featuresText.value.split("\n").map(line => line.trim()).filter(line => line.length > 0);
        featuresHidden.value = JSON.stringify(featuresArray);
    });
});
</script>
@endpush

@endsection
