@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">Rankolab Setup Wizard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route("admin.setup.wizard.index") }}">Setup Wizard</a></li>
        <li class="breadcrumb-item active">Step 2: Site & Admin</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-cogs me-1"></i>
            Step 2: Site Information & Admin Account
        </div>
        <div class="card-body">
            @include("partials.flash_messages")
            @include("partials.validation_errors")

            <form action="{{ route("admin.setup.wizard.step") }}" method="POST">
                @csrf
                <input type="hidden" name="current_step" value="2">

                <fieldset>
                    <legend>Site Information</legend>
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old("site_name", config("app.name")) }}" required>
                    </div>
                    {{-- Add Site URL if needed --}}
                </fieldset>

                <fieldset class="mt-4">
                    <legend>Super Admin Account</legend>
                    <p>Configure the primary administrator account.</p>
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Admin Email</label>
                        <input type="email" class="form-control" id="admin_email" name="admin_email" value="{{ old("admin_email", $superAdmin->email ?? "") }}" required>
                        <small class="form-text text-muted">This email will be used for the super admin login.</small>
                    </div>
                     <div class="mb-3">
                        <label for="admin_password" class="form-label">Admin Password</label>
                        <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                        <small class="form-text text-muted">Choose a strong password.</small>
                    </div>
                     <div class="mb-3">
                        <label for="admin_password_confirmation" class="form-label">Confirm Admin Password</label>
                        <input type="password" class="form-control" id="admin_password_confirmation" name="admin_password_confirmation" required>
                    </div>
                    {{-- TODO: Fetch existing super admin email if available --}}
                </fieldset>

                <button type="submit" class="btn btn-primary">Next Step</button>
                <a href="{{ route("admin.setup.wizard.index") }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection

