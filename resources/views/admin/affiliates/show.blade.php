@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">View Affiliate Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route("admin.affiliates.index") }}">Affiliates</a></li>
        <li class="breadcrumb-item active">View (ID: {{ $affiliate->id }})</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-eye me-1"></i>
            Affiliate Information
            <a href="{{ route("admin.affiliates.edit", $affiliate->id) }}" class="btn btn-warning btn-sm float-end ms-2">Edit</a>
            <a href="{{ route("admin.affiliates.index") }}" class="btn btn-secondary btn-sm float-end">Back to List</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $affiliate->id }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $affiliate->user->name ?? "N/A" }} ({{ $affiliate->user->email ?? "N/A" }})</td>
                    </tr>
                    <tr>
                        <th>Affiliate Code</th>
                        <td>{{ $affiliate->code }}</td>
                    </tr>
                    <tr>
                        <th>Commission Rate (%)</th>
                        <td>{{ $affiliate->commission_rate }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge bg-{{ $affiliate->status == "active" ? "success" : "secondary" }}">{{ ucfirst($affiliate->status) }}</span></td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $affiliate->created_at->format("Y-m-d H:i:s") }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $affiliate->updated_at->format("Y-m-d H:i:s") }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Section for Commissions --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-hand-holding-usd me-1"></i>
            Commissions ({{ $affiliate->commissions->count() }})
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Referral ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($affiliate->commissions as $commission)
                        <tr>
                            <td>{{ $commission->id }}</td>
                            <td>{{ $commission->referral_id }}</td>
                            <td>${{ number_format($commission->amount, 2) }}</td>
                            <td>{{ ucfirst($commission->status) }}</td>
                            <td>{{ $commission->created_at->format("Y-m-d") }}</td>
                            <td>
                                <a href="{{ route("admin.commissions.show", $commission->id) }}" class="btn btn-info btn-sm">View</a>
                                {{-- Add Approve/Reject buttons if applicable --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No commissions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Add pagination if needed --}}
        </div>
    </div>

    {{-- Section for Referrals --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Referrals ({{ $affiliate->referrals->count() }})
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Referred User ID</th> {{-- Assuming you store the ID of the user who was referred --}}
                        <th>Conversion Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($affiliate->referrals as $referral)
                        <tr>
                            <td>{{ $referral->id }}</td>
                            <td>{{ $referral->referred_user_id ?? "N/A" }}</td> {{-- Adjust field name as needed --}}
                            <td>{{ $referral->conversion_date ? $referral->conversion_date->format("Y-m-d") : "N/A" }}</td>
                            <td>{{ ucfirst($referral->status) }}</td>
                            <td>
                                <a href="{{ route("admin.referrals.show", $referral->id) }}" class="btn btn-info btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No referrals found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Add pagination if needed --}}
        </div>
    </div>

</div>
@endsection

