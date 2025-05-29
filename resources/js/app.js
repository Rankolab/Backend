// Dashboard component with charts and stats
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueChartEl = document.getElementById('revenue-chart');
    if (revenueChartEl) {
        const ctx = revenueChartEl.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(61, 213, 152, 0.5)');
        gradient.addColorStop(1, 'rgba(77, 157, 224, 0.1)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [1200, 1900, 3000, 5000, 4000, 6000, 7000, 6500, 8000, 8500, 9000, 9500],
                    borderColor: '#3DD598',
                    backgroundColor: gradient,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(200, 200, 200, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // User Growth Chart
    const userChartEl = document.getElementById('user-growth-chart');
    if (userChartEl) {
        const ctx = userChartEl.getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'New Users',
                    data: [50, 80, 120, 160, 200, 240],
                    backgroundColor: '#4D9DE0',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(200, 200, 200, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // Traffic Sources Chart
    const trafficChartEl = document.getElementById('traffic-sources-chart');
    if (trafficChartEl) {
        const ctx = trafficChartEl.getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Organic', 'Referral', 'Direct', 'Social'],
                datasets: [{
                    data: [45, 25, 20, 10],
                    backgroundColor: ['#3DD598', '#4D9DE0', '#6B7280', '#F59E0B'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });
    }
});

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();
