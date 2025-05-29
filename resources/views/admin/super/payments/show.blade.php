@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">License Payment Details</h3>
                    <div>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Payments
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>License Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">License ID</th>
                                            <td>{{ $payment->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>License Key</th>
                                            <td>{{ $payment->license_key }}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount</th>
                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $payment->status === 'active' ? 'success' : 
                                                    ($payment->status === 'pending' ? 'warning' : 
                                                    ($payment->status === 'expired' ? 'info' : 'danger')) 
                                                }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>{{ ucfirst($payment->type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date</th>
                                            <td>{{ $payment->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Expiry Date</th>
                                            <td>{{ $payment->expires_at ? $payment->expires_at->format('M d, Y') : 'Never' }}</td>
                                        </tr>
                                        @if($payment->status === 'inactive' && strpos($payment->notes, 'Refund reason:') !== false)
                                        <tr>
                                            <th>Refund Information</th>
                                            <td>{{ substr($payment->notes, strpos($payment->notes, 'Refund reason:')) }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Customer Information</h4>
                                </div>
                                <div class="card-body">
                                    @if($payment->user)
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Name</th>
                                            <td>{{ $payment->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $payment->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>User ID</th>
                                            <td>{{ $payment->user->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Registered</th>
                                            <td>{{ $payment->user->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td>{{ ucfirst($payment->user->role) }}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.users.show', $payment->user->id) }}" class="btn btn-info">
                                            <i class="fas fa-user"></i> View User Profile
                                        </a>
                                    </div>
                                    @else
                                    <div class="alert alert-warning">
                                        User information not available
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#updateStatusModal">
                                                <i class="fas fa-edit"></i> Update Status
                                            </button>
                                        </div>
                                        
                                        @if($payment->status === 'active')
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#refundModal">
                                                <i class="fas fa-undo"></i> Process Refund
                                            </button>
                                        </div>
                                        @endif
                                        
                                        <div class="col-md-4">
                                            <a href="#" class="btn btn-info btn-block" onclick="window.print()">
                                                <i class="fas fa-print"></i> Print Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update License Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.payments.update-status', $payment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" {{ $payment->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="inactive" {{ $payment->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="expired" {{ $payment->status === 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="refundModalLabel">Process Refund</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.payments.refund', $payment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Warning: This action will mark the license as inactive.
                    </div>
                    
                    <div class="form-group">
                        <label for="refund_reason">Refund Reason</label>
                        <textarea name="refund_reason" id="refund_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Process Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
