@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">Edit Affiliate</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route("admin.affiliates.index") }}">Affiliates</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Affiliate Record (ID: {{ $affiliate->id }})
        </div>
        <div class="card-body">
            @include("partials.validation_errors")

            <form action="{{ route("admin.affiliates.update", $affiliate->id) }}" method="POST">
                @csrf
                @method("PUT")

                <div class="mb-3">
                    <label for="user_id" class="form-label">User</label>
                    <input type="text" class="form-control" id="user_name" value="{{ $affiliate->user->name ?? "N/A" }} ({{ $affiliate->user->email ?? "N/A" }})" disabled>
                    {{-- Keep user_id hidden or simply don't allow changing it --}}
                    <input type="hidden" name="user_id" value="{{ $affiliate->user_id }}">
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">Affiliate Code</label>
                    <input type="text" class="form-control" id="code" value="{{ $affiliate->code }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                    <input type="number" class="form-control" id="commission_rate" name="commission_rate" value="{{ old("commission_rate", $affiliate->commission_rate) }}" min="0" max="100" step="0.1" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="active" {{ old("status", $affiliate->status) == "active" ? "selected" : "" }}>Active</option>
                        <option value="inactive" {{ old("status", $affiliate->status) == "inactive" ? "selected" : "" }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Affiliate</button>
                <a href="{{ route("admin.affiliates.index") }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection

