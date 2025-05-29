@extends('layouts.app')

@section('title', 'AI Agent Dashboard')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">AI Agent Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Super Admin AI Management System</li>
    </ol>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-robot me-2"></i>AI Command Center</h4>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" id="ai-command" class="form-control form-control-lg" 
                            placeholder="Enter command (e.g., 'get system status', 'show user statistics')" 
                            aria-label="AI Command">
                        <button class="btn btn-light" type="button" id="send-command">
                            <i class="fas fa-paper-plane"></i> Send
                        </button>
                    </div>
                    <small class="text-white-50">Type natural language commands to manage the system</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-terminal me-2"></i>Response Console</h4>
                </div>
                <div class="card-body bg-dark text-light p-3" id="response-console" style="min-height: 200px; max-height: 400px; overflow-y: auto; font-family: monospace;">
                    <div class="console-line">
                        <span class="text-success">AI Agent</span>: <span class="text-white">System initialized. Ready for commands.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>System Status</h5>
                            <h2 id="system-status">Operational</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-server"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="text-white stretched-link" href="#" id="check-system">Check System Status</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>User Management</h5>
                            <h2 id="user-count">{{ $systemStatus['users']['total'] ?? 0 }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="text-white stretched-link" href="{{ route('admin.aiagent.users') }}">Manage Users</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Content</h5>
                            <h2 id="content-count">{{ $systemStatus['content']['articles'] ?? 0 }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="text-white stretched-link" href="{{ route('admin.aiagent.content') }}">Manage Content</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Security</h5>
                            <h2 id="security-status">Protected</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="text-white stretched-link" href="{{ route('admin.aiagent.security') }}">Security Audit</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    User Growth
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Revenue Trends
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Growth Chart
        const userCtx = document.getElementById('userGrowthChart').getContext('2d');
        const userGrowthChart = new Chart(userCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'New Users',
                    data: [65, 78, 90, 115, 123, 142, 168],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [12500, 15700, 18900, 22400, 25800, 29300, 34500],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        // AI Command Processing
        const commandInput = document.getElementById('ai-command');
        const sendButton = document.getElementById('send-command');
        const responseConsole = document.getElementById('response-console');
        
        function addConsoleResponse(sender, message, isError = false) {
            const line = document.createElement('div');
            line.className = 'console-line mb-2';
            
            const senderSpan = document.createElement('span');
            senderSpan.className = sender === 'AI Agent' ? 'text-success' : 'text-primary';
            senderSpan.textContent = sender + ': ';
            
            const messageSpan = document.createElement('span');
            messageSpan.className = isError ? 'text-danger' : 'text-white';
            messageSpan.textContent = message;
            
            line.appendChild(senderSpan);
            line.appendChild(messageSpan);
            
            responseConsole.appendChild(line);
            responseConsole.scrollTop = responseConsole.scrollHeight;
        }
        
        function processCommand() {
            const command = commandInput.value.trim();
            if (!command) return;
            
            addConsoleResponse('You', command);
            commandInput.value = '';
            
            // Send command to server
            fetch('{{ route("admin.aiagent.process-command") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ command: command })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    addConsoleResponse('AI Agent', data.message, true);
                } else {
                    addConsoleResponse('AI Agent', 'Command processed successfully.');
                    
                    // Update dashboard with new data if available
                    if (data.data) {
                        if (data.data.users) {
                            document.getElementById('user-count').textContent = data.data.users.total;
                        }
                        if (data.data.content) {
                            document.getElementById('content-count').textContent = data.data.content.articles;
                        }
                        
                        // Display detailed response
                        addConsoleResponse('AI Agent', JSON.stringify(data.data, null, 2));
                    }
                }
            })
            .catch(error => {
                addConsoleResponse('AI Agent', 'Error processing command: ' + error.message, true);
            });
        }
        
        sendButton.addEventListener('click', processCommand);
        commandInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                processCommand();
            }
        });
        
        // System status check
        document.getElementById('check-system').addEventListener('click', function(e) {
            e.preventDefault();
            commandInput.value = 'get system status';
            processCommand();
        });
    });
</script>
@endsection
