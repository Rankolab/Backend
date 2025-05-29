@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Management</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Revenue</h5>
                                    <h2 class="display-4">${{ number_format($totalRevenue, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Monthly Revenue</h5>
                                    <h2 class="display-4">${{ number_format($monthlyRevenue, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Licenses</h5>
                                    <h2 class="display-4">{{ $pendingPayments }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Actions</h5>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.payments.analytics') }}" class="btn btn-light">Analytics</a>
                                        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#exportModal">
                                            Export
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h4>License Payments</h4>
                            <div class="card-tools">
                                <form action="{{ route('admin.payments.index') }}" method="GET" class="form-inline">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="search" class="form-control" placeholder="Search licenses...">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>License Key</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>
                                                @if($payment->user)
                                                    <a href="{{ route('admin.users.show', $payment->user->id) }}">
                                                        {{ $payment->user->name }}
                                                    </a>
                                                @else
                                                    Unknown User
                                                @endif
                                            </td>
                                            <td>{{ $payment->license_key }}</td>
                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ ucfirst($payment->type) }}</td>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $payment->status === 'active' ? 'success' : 
                                                    ($payment->status === 'pending' ? 'warning' : 
                                                    ($payment->status === 'expired' ? 'info' : 'danger')) 
                                                }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#statusModal{{ $payment->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Status Update Modal -->
                                                <div class="modal fade" id="statusModal{{ $payment->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel{{ $payment->id }}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="statusModalLabel{{ $payment->id }}">Update License Status</h5>
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
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ $payments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export License Payments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.payments.export') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="format">Export Format</label>
                        <select name="format" id="format" class="form-control">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
