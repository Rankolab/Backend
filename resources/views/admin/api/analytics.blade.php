@extends('layouts.app')

@section('title', 'API Analytics')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">API Analytics</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">API Analytics</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">Success vs Failure</div>
        <div class="card-body">
            <canvas id="apiChart" width="100%" height="40"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('apiChart').getContext('2d');
    const apiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Success',
                data: {!! json_encode($successCounts) !!},
                backgroundColor: 'rgba(25, 135, 84, 0.8)',
            },
            {
                label: 'Failures',
                data: {!! json_encode($failCounts) !!},
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'API Call Success vs Failures' }
            }
        }
    });
</script>
@endsection
