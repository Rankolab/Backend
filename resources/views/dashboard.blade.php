<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rankolab Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Rankolab Logo" class="w-36 h-auto mx-auto mb-6">
            </div>
            <nav class="sidebar-nav">
                <div class="nav-category">Main</div>
                <div class="nav-item active">
                    <div class="nav-icon"><i class="fas fa-tachometer-alt"></i></div>
                    <span>Dashboard</span>
                </div>
                <div class="nav-item">
                    <div class="nav-icon"><i class="fas fa-chart-line"></i></div>
                    <span>Analytics</span>
                </div>
                <div class="nav-item">
                    <div class="nav-icon"><i class="fas fa-globe"></i></div>
                    <span>Websites</span>
                </div>
                
                <div class="nav-category">SEO Tools</div>
                <div class="nav-item">
                    <div class="nav-icon"><i class="fas fa-search"></i></div>
                    <span>Keyword Research</span>
                </div>
                <div class="nav-item">
                    <div class="nav-icon"><i class="fas fa-link"></i></div>
                    <span>Backlink Analysis</span>
                </div>
                <div class="nav-item">
                    <div class="nav-icon"><i class="fas fa-sitemap"></i></div>
                    <span>Site Audit</span>
                </div>
                
                <div class="nav-category">Content</div>
                <div class="nav-item">
                    <div class="nav-icon"><i class="fas fa-file-alt"></i></div>
                    <span>Content Manager</span>
                </div>
                <div class="nav-item">
                    <div class="nav-icon"><i class="fas fa-tasks"></i></div>
                    <span>Content Calendar</span>
                </div>
                
                <div class="nav-category">Management</div>
                <div class="nav-item">
                    <div class="nav-icon"><i class="fas fa-users"></i></div>
                    <span>User Management</span>
                </div>
                <div class="nav-item">
                    <div class="nav-icon"><i class="fas fa-cog"></i></div>
                    <span>Settings</span>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <button class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="header-title">Dashboard Overview</h1>
                <div class="header-actions">
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="unread-badge">3</span>
                    </div>
                    <button class="dark-mode-toggle" aria-label="Toggle Dark Mode">
                        <i class="fas fa-moon"></i>
                    </button>
                    <div class="user-profile">
                        <img src="https://via.placeholder.com/40" alt="User Profile" class="profile-image">
                    </div>
                </div>
            </header>

            <div class="dashboard-summary mt-20">
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Website Traffic</h2>
                            <div class="card-actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="stat-value">12,345</div>
                            <div class="stat-change text-success">
                                <i class="fas fa-arrow-up"></i> 12.5%
                            </div>
                        </div>
                        <div class="card-footer">
                            <span>Last 30 days</span>
                            <a href="#" class="view-details">View Details</a>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Keyword Rankings</h2>
                            <div class="card-actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="stat-value">87</div>
                            <div class="stat-change text-success">
                                <i class="fas fa-arrow-up"></i> 5.2%
                            </div>
                        </div>
                        <div class="card-footer">
                            <span>Top 10 positions</span>
                            <a href="#" class="view-details">View Details</a>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Backlinks</h2>
                            <div class="card-actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="stat-value">543</div>
                            <div class="stat-change text-success">
                                <i class="fas fa-arrow-up"></i> 8.7%
                            </div>
                        </div>
                        <div class="card-footer">
                            <span>Total backlinks</span>
                            <a href="#" class="view-details">View Details</a>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Page Speed</h2>
                            <div class="card-actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="stat-value">85</div>
                            <div class="stat-change text-warning">
                                <i class="fas fa-arrow-down"></i> 2.1%
                            </div>
                        </div>
                        <div class="card-footer">
                            <span>Average score</span>
                            <a href="#" class="view-details">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-charts mt-20">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Traffic Analytics</h2>
                        <div class="card-actions">
                            <select class="chart-period-selector">
                                <option value="7">Last 7 days</option>
                                <option value="30" selected>Last 30 days</option>
                                <option value="90">Last 90 days</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <canvas id="analyticsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid mt-20">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Performance Metrics</h2>
                        <div class="card-actions">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Traffic Distribution</h2>
                        <div class="card-actions">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <canvas id="distributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-20">
                <div class="card-header">
                    <h2 class="card-title">Recent Activities</h2>
                    <div class="card-actions">
                        <input type="text" class="table-search" placeholder="Search activities...">
                    </div>
                </div>
                <div class="card-content">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="sortable">Date</th>
                                <th class="sortable">Activity</th>
                                <th class="sortable">User</th>
                                <th class="sortable">Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2025-05-18</td>
                                <td>Website Audit Completed</td>
                                <td>John Smith</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td><a href="#"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            <tr>
                                <td>2025-05-17</td>
                                <td>Keyword Research</td>
                                <td>Emma Johnson</td>
                                <td><span class="badge bg-info">In Progress</span></td>
                                <td><a href="#"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            <tr>
                                <td>2025-05-16</td>
                                <td>Content Optimization</td>
                                <td>Michael Brown</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td><a href="#"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            <tr>
                                <td>2025-05-15</td>
                                <td>Backlink Analysis</td>
                                <td>Sarah Davis</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td><a href="#"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            <tr>
                                <td>2025-05-14</td>
                                <td>Technical SEO Audit</td>
                                <td>Robert Wilson</td>
                                <td><span class="badge bg-danger">Failed</span></td>
                                <td><a href="#"><i class="fas fa-eye"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="pagination">
                        <button class="btn btn-secondary"><i class="fas fa-chevron-left"></i></button>
                        <span>Page 1 of 5</span>
                        <button class="btn btn-secondary"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Notification Panel -->
    <div class="notification-panel">
        <div class="notification-header">
            <h3>Notifications</h3>
            <a href="#">Mark all as read</a>
        </div>
        <div class="notification-list">
            <div class="notification-item unread">
                <div class="notification-icon bg-info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">Traffic Spike Detected</div>
                    <div class="notification-text">Your website traffic increased by 25% in the last hour.</div>
                    <div class="notification-time">10 minutes ago</div>
                </div>
            </div>
            <div class="notification-item unread">
                <div class="notification-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">Keyword Ranking Improved</div>
                    <div class="notification-text">3 keywords moved to the first page of search results.</div>
                    <div class="notification-time">1 hour ago</div>
                </div>
            </div>
            <div class="notification-item unread">
                <div class="notification-icon bg-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">Backlink Lost</div>
                    <div class="notification-text">A high-quality backlink from example.com was removed.</div>
                    <div class="notification-time">3 hours ago</div>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">Page Speed Issue</div>
                    <div class="notification-text">Your homepage loading speed has decreased.</div>
                    <div class="notification-time">Yesterday</div>
                </div>
            </div>
        </div>
        <div class="notification-footer">
            <a href="#">View all notifications</a>
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
