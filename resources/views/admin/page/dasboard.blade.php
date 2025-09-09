@extends('admin.layout.master')

@section('content')
    <div class="main-content">
        <div class="top-bar">
        <div class="page-title">
            <h1>Dashboard</h1>
            <div class="breadcrumb">Home / Dashboard</div>
        </div>

        <div class="user-info">
            <button class="notification-btn">
                <i class="material-icons">notifications</i>
                <span class="notification-badge">3</span>
            </button>

            <div class="user-avatar">
                <i class="material-icons">person</i>
            </div>
        </div>
    </div>
        <div class="dashboard-content">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon products">
                            <i class="material-icons">inventory_2</i>
                        </div>
                        <div class="stat-change positive">+12.5%</div>
                    </div>
                    <div class="stat-value">1,234</div>
                    <div class="stat-label">Total Products</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon orders">
                            <i class="material-icons">shopping_cart</i>
                        </div>
                        <div class="stat-change positive">+8.2%</div>
                    </div>
                    <div class="stat-value">567</div>
                    <div class="stat-label">Total Orders</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon users">
                            <i class="material-icons">people</i>
                        </div>
                        <div class="stat-change positive">+15.3%</div>
                    </div>
                    <div class="stat-value">2,891</div>
                    <div class="stat-label">Total Users</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon revenue">
                            <i class="material-icons">payments</i>
                        </div>
                        <div class="stat-change negative">-2.4%</div>
                    </div>
                    <div class="stat-value">Rp 45.2M</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="chart-container">
                <div class="chart-card">
                    <h3 class="chart-title">Sales Analytics</h3>
                    <div class="chart-placeholder">
                        <i class="material-icons" style="font-size: 48px; margin-right: 10px;">bar_chart</i>
                        Sales Chart Will Be Here
                    </div>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">Revenue Distribution</h3>
                    <div class="chart-placeholder">
                        <i class="material-icons" style="font-size: 48px; margin-right: 10px;">pie_chart</i>
                        Pie Chart Will Be Here
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="recent-activity">
                <h3 class="chart-title">Recent Activity</h3>

                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="material-icons">shopping_cart</i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New order #1234 received</div>
                        <div class="activity-time">2 minutes ago</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="material-icons">person_add</i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New user registered</div>
                        <div class="activity-time">5 minutes ago</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="material-icons">inventory</i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Product stock updated</div>
                        <div class="activity-time">12 minutes ago</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="material-icons">payment</i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Payment received for order #1230</div>
                        <div class="activity-time">1 hour ago</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="material-icons">star</i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New product review submitted</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
