@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">Rankolab Setup Wizard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route("admin.setup.wizard.index") }}">Setup Wizard</a></li>
        <li class="breadcrumb-item active">Complete</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-check-circle me-1"></i>
            Setup Complete!
        </div>
        <div class="card-body">
            @include("partials.flash_messages")

            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Congratulations!</h4>
                <p>You have successfully completed the initial setup for the Rankolab backend system.</p>
                <hr>
                <p class="mb-0">You can now proceed to the main dashboard to manage your system.</p>
            </div>

            <a href="{{ route("admin.dashboard") }}" class="btn btn-primary">Go to Dashboard</a>
        </div>
    </div>
</div>
@endsection

