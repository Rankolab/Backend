@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">Rankolab Setup Wizard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Setup Wizard</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-magic me-1"></i>
            Step 1: Welcome
        </div>
        <div class="card-body">
            @include("partials.flash_messages")

            <p>Welcome to the Rankolab setup wizard! This wizard will guide you through the initial configuration of your Rankolab backend system.</p>
            
            <p>In the next steps, you will configure essential settings such as:</p>
            <ul>
                <li>Site Information (Name, URL)</li>
                <li>Admin Account Details</li>
                <li>Email Configuration</li>
                <li>API Keys (e.g., Stripe, Google Analytics - optional)</li>
                {{-- Add more items as the wizard evolves --}}
            </ul>

            <form action="{{ route("admin.setup.wizard.step") }}" method="POST">
                @csrf
                <input type="hidden" name="current_step" value="1">
                {{-- Add fields for Step 1 if any, otherwise just proceed --}}
                
                <button type="submit" class="btn btn-primary">Start Setup</button>
            </form>

            {{-- Option to skip wizard if configuration already exists --}}
            {{-- @if ($is_configured) --}}
            {{-- <div class="mt-3">
                <a href="{{ route("admin.dashboard") }}" class="btn btn-secondary">Skip Wizard (Already Configured)</a>
            </div> --}}
            {{-- @endif --}}
        </div>
    </div>
</div>
@endsection

