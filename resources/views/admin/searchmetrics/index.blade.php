@extends('layouts.app')

@section('title', 'Search Console Metrics')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Search Console Metrics</h1>
    <div class="card mb-4 mt-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Domain</th>
                        <th>Query</th>
                        <th>Clicks</th>
                        <th>Impressions</th>
                        <th>CTR (%)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($metrics as $row)
                        <tr>
                            <td>{{ $row->website->domain }}</td>
                            <td>{{ $row->query }}</td>
                            <td>{{ $row->clicks }}</td>
                            <td>{{ $row->impressions }}</td>
                            <td>{{ number_format($row->ctr, 2) }}</td>
                            <td>{{ $row->date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
