@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">AI Super Agent</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users</h5>
                                    <h2 class="display-4">{{ $totalUsers }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Admin Users</h5>
                                    <h2 class="display-4">{{ $adminUsers }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Super Admins</h5>
                                    <h2 class="display-4">{{ $superAdmins }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>AI Super Agent Command Interface</h4>
                                </div>
                                <div class="card-body">
                                    <form id="agentForm">
                                        <div class="input-group mb-3">
                                            <input type="text" id="commandInput" class="form-control" placeholder="Enter your command...">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Send</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <div class="mt-4">
                                        <h5>Response:</h5>
                                        <div id="responseArea" class="p-3 bg-light rounded">
                                            <p class="text-muted">AI Super Agent is ready. Type a command to begin.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <h5>Example Commands:</h5>
                                        <ul class="list-group">
                                            <li class="list-group-item">How many users are in the system?</li>
                                            <li class="list-group-item">List all administrators</li>
                                            <li class="list-group-item">Show system status</li>
                                        </ul>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#agentForm').on('submit', function(e) {
            e.preventDefault();
            
            const command = $('#commandInput').val();
            if (!command) return;
            
            // Show loading state
            $('#responseArea').html('<p>Processing...</p>');
            
            // Send command to server
            $.ajax({
                url: '{{ route("super.agent.ask") }}',
                method: 'POST',
                data: {
                    command: command,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#responseArea').html('<p>' + response.response + '</p>');
                },
                error: function(error) {
                    $('#responseArea').html('<p class="text-danger">Error processing command. Please try again.</p>');
                    console.error(error);
                }
            });
            
            // Clear input
            $('#commandInput').val('');
        });
    });
</script>
@endpush
@endsection
