@extends("layouts.app")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">Add New Affiliate</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route("admin.affiliates.index") }}">Affiliates</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            Create Affiliate Record
        </div>
        <div class="card-body">
            @include("partials.validation_errors")

            <form action="{{ route("admin.affiliates.store") }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="user_id" class="form-label">User</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <option value="">Select User</option>
                        {{-- TODO: Populate with users who are not already affiliates --}}
                        @foreach (\App\Models\User::orderBy("name")->get() as $user)
                            <option value="{{ $user->id }}" {{ old("user_id") == $user->id ? "selected" : "" }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                    <input type="number" class="form-control" id="commission_rate" name="commission_rate" value="{{ old("commission_rate", 10) }}" min="0" max="100" step="0.1" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="active" {{ old("status", "active") == "active" ? "selected" : "" }}>Active</option>
                        <option value="inactive" {{ old("status") == "inactive" ? "selected" : "" }}>Inactive</option>
                    </select>
                </div>

                {{-- Affiliate code will be generated automatically --}}

                <button type="submit" class="btn btn-primary">Create Affiliate</button>
                <a href="{{ route("admin.affiliates.index") }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection

