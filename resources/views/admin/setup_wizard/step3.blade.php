@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">Rankolab Setup Wizard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route("admin.setup.wizard.index") }}">Setup Wizard</a></li>
        <li class="breadcrumb-item active">Step 3: Email Configuration</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-envelope me-1"></i>
            Step 3: Email Configuration
        </div>
        <div class="card-body">
            @include("partials.flash_messages")
            @include("partials.validation_errors")

            <p>Configure how the system sends emails (e.g., for notifications, password resets).</p>

            <form action="{{ route("admin.setup.wizard.step") }}" method="POST">
                @csrf
                <input type="hidden" name="current_step" value="3">

                <div class="mb-3">
                    <label for="mail_mailer" class="form-label">Mail Driver</label>
                    <select class="form-control" id="mail_mailer" name="mail_mailer" required>
                        <option value="smtp" {{ old("mail_mailer", config("mail.default")) == "smtp" ? "selected" : "" }}>SMTP</option>
                        <option value="log" {{ old("mail_mailer", config("mail.default")) == "log" ? "selected" : "" }}>Log (For Development)</option>
                        <option value="sendmail" {{ old("mail_mailer", config("mail.default")) == "sendmail" ? "selected" : "" }}>Sendmail</option>
                        {{-- Add other drivers like mailgun, ses, etc. if needed --}}
                    </select>
                </div>

                <div class="mb-3">
                    <label for="mail_host" class="form-label">SMTP Host</label>
                    <input type="text" class="form-control" id="mail_host" name="mail_host" value="{{ old("mail_host", config("mail.mailers.smtp.host")) }}">
                </div>

                <div class="mb-3">
                    <label for="mail_port" class="form-label">SMTP Port</label>
                    <input type="number" class="form-control" id="mail_port" name="mail_port" value="{{ old("mail_port", config("mail.mailers.smtp.port")) }}">
                </div>

                <div class="mb-3">
                    <label for="mail_username" class="form-label">SMTP Username</label>
                    <input type="text" class="form-control" id="mail_username" name="mail_username" value="{{ old("mail_username", config("mail.mailers.smtp.username")) }}">
                </div>

                <div class="mb-3">
                    <label for="mail_password" class="form-label">SMTP Password</label>
                    <input type="password" class="form-control" id="mail_password" name="mail_password" value="{{ old("mail_password", config("mail.mailers.smtp.password")) }}">
                </div>

                <div class="mb-3">
                    <label for="mail_encryption" class="form-label">SMTP Encryption</label>
                    <select class="form-control" id="mail_encryption" name="mail_encryption">
                        <option value="" {{ old("mail_encryption", config("mail.mailers.smtp.encryption")) == "" ? "selected" : "" }}>None</option>
                        <option value="tls" {{ old("mail_encryption", config("mail.mailers.smtp.encryption")) == "tls" ? "selected" : "" }}>TLS</option>
                        <option value="ssl" {{ old("mail_encryption", config("mail.mailers.smtp.encryption")) == "ssl" ? "selected" : "" }}>SSL</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="mail_from_address" class="form-label">From Email Address</label>
                    <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" value="{{ old("mail_from_address", config("mail.from.address")) }}" required>
                </div>

                <div class="mb-3">
                    <label for="mail_from_name" class="form-label">From Name</label>
                    <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" value="{{ old("mail_from_name", config("mail.from.name")) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Complete Setup</button> {{-- Assuming this is the last step --}}
                <a href="{{ route("admin.setup.wizard.step", ["current_step" => 2]) }}" class="btn btn-secondary">Back</a> {{-- Adjust route if needed --}}
            </form>
        </div>
    </div>
</div>
@endsection

