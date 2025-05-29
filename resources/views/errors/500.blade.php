@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900 px-4">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-green-500 dark:text-green-400">500</h1>
        <h2 class="text-4xl font-bold text-gray-800 dark:text-white mt-4">Server Error</h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 mt-4">Sorry, something went wrong on our servers. We're working to fix the issue.</p>
        <div class="mt-8">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-5 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Return to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
