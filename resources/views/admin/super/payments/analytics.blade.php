@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Analytics</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Monthly Revenue (Last 12 Months)</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="revenueChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Payment Methods</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="paymentMethodsChart" height="260"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Top Customers</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Email</th>
                                                    <th>Total Spent</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topCustomersData as $customer)
                                                <tr>
                                                    <td>{{ $customer['name'] }}</td>
                                                    <td>{{ $customer['email'] }}</td>
                                                    <td>${{ number_format($customer['total'], 2) }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.users.show', $customer['id'] ?? 0) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>License Status Distribution</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="statusChart" height="260"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>License Type Distribution</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="licenseTypeChart" height="260"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Monthly Revenue',
                    data: {!! json_encode($data) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
        
        // Payment Methods Chart
        const methodLabels = [];
        const methodData = [];
        const backgroundColors = [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)'
        ];
        
        @foreach($paymentMethods as $method)
            methodLabels.push('{{ ucfirst($method->payment_method) }}');
            methodData.push({{ $method->count }});
        @endforeach
        
        const methodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        const methodsChart = new Chart(methodsCtx, {
            type: 'doughnut',
            data: {
                labels: methodLabels,
                datasets: [{
                    data: methodData,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(color => color.replace('0.5', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
        
        // License status chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['Active', 'Pending', 'Inactive', 'Expired'],
                datasets: [{
                    data: [65, 15, 10, 10],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.5)',
                        'rgba(255, 193, 7, 0.5)',
                        'rgba(220, 53, 69, 0.5)',
                        'rgba(23, 162, 184, 0.5)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(23, 162, 184, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
        
        // License type chart
        const licenseTypeLabels = [];
        const licenseTypeData = [];
        
        @foreach($licenseTypes as $type)
            licenseTypeLabels.push('{{ ucfirst($type->type) }}');
            licenseTypeData.push({{ $type->count }});
        @endforeach
        
        const licenseCtx = document.getElementById('licenseTypeChart').getContext('2d');
        const licenseChart = new Chart(licenseCtx, {
            type: 'pie',
            data: {
                labels: licenseTypeLabels.length > 0 ? licenseTypeLabels : ['Basic', 'Pro', 'Enterprise', 'Custom'],
                datasets: [{
                    data: licenseTypeData.length > 0 ? licenseTypeData : [30, 40, 20, 10],
                    backgroundColor: [
                        'rgba(108, 117, 125, 0.5)',
                        'rgba(0, 123, 255, 0.5)',
                        'rgba(102, 16, 242, 0.5)',
                        'rgba(23, 162, 184, 0.5)'
                    ],
                    borderColor: [
                        'rgba(108, 117, 125, 1)',
                        'rgba(0, 123, 255, 1)',
                        'rgba(102, 16, 242, 1)',
                        'rgba(23, 162, 184, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
