@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="page-title mb-0">Bảng Điều Khiển Admin</h2>
    </div>

    <!-- ========================================== -->
    <!-- TOP ROW: SUMMARY CARDS -->
    <!-- ========================================== -->
    <div class="row mb-4">
        <!-- Doanh thu tháng này -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card admin-card border-left-primary h-100 py-2" style="border-left: 4px solid #4e73df;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="font-size: 13px; font-weight: 700;">
                                Doanh thu tháng này ({{ $now->format('m/Y') }})
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" style="font-size: 24px; font-weight: 800; color: #5a5c69;">
                                {{ number_format($totalRevenueThisMonth, 0, ',', '.') }}₫
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300" style="color: #dddfeb; font-size: 2em;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doanh thu năm nay -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card admin-card border-left-success h-100 py-2" style="border-left: 4px solid #1cc88a;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 13px; font-weight: 700;">
                                Tổng doanh thu năm nay ({{ $now->year }})
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" style="font-size: 24px; font-weight: 800; color: #5a5c69;">
                                {{ number_format($totalRevenueThisYear, 0, ',', '.') }}₫
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300" style="color: #dddfeb; font-size: 2em;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng vé bán ra -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card admin-card border-left-warning h-100 py-2" style="border-left: 4px solid #f6c23e;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="font-size: 13px; font-weight: 700;">
                                Tổng vé đã bán
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" style="font-size: 24px; font-weight: 800; color: #5a5c69;">
                                {{ number_format($totalTicketsSold) }} Vé
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300" style="color: #dddfeb; font-size: 2em;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MIDDLE ROW: REVENUE CHARTS -->
    <!-- ========================================== -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card admin-card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-bar me-1"></i> Doanh thu theo ngày (Tháng {{ $now->month }})</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card admin-card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-chart-area me-1"></i> Doanh thu theo tháng (Năm {{ $now->year }})</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- BOTTOM ROW: ROUTE PERFORMANCE -->
    <!-- ========================================== -->
    <div class="row">
        <!-- Most Booked Routes -->
        <div class="col-lg-6 mb-4">
            <div class="card admin-card mb-4">
                <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fire text-danger me-1"></i> Tuyến xe chạy nhiều nhất</h6>
                </div>
                <div class="card-body">
                    @forelse($mostBookedRoutes as $route)
                        @php
                            $percentage = ($route->ticket_count / $maxTickets) * 100;
                        @endphp
                        <h4 class="small font-weight-bold">{{ $route->route_name }} <span class="float-end">{{ $route->ticket_count }} vé</span></h4>
                        <div class="progress mb-4" style="height: 10px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Chưa có dữ liệu chuyến xe</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Least Booked Routes -->
        <div class="col-lg-6 mb-4">
            <div class="card admin-card mb-4">
                <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-snowflake text-info me-1"></i> Tuyến xe chạy ít nhất</h6>
                </div>
                <div class="card-body">
                    @forelse($leastBookedRoutes as $route)
                        @php
                            $percentage = ($route->ticket_count / $maxTickets) * 100;
                        @endphp
                        <h4 class="small font-weight-bold">{{ $route->route_name }} <span class="float-end">{{ $route->ticket_count }} vé</span></h4>
                        <div class="progress mb-4" style="height: 10px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Chưa có dữ liệu chuyến xe</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Parse JSON data from Backend
        const dailyDates = {!! $dailyDates !!};
        const dailyLabels = {!! $dailyLabels !!};
        const dailyRevenues = {!! $dailyRevenues !!};
        const dailyTicketCounts = {!! $dailyTicketCounts !!};

        const monthlyDates = {!! $monthlyDates !!};
        const monthlyLabels = {!! $monthlyLabels !!};
        const monthlyRevenues = {!! $monthlyRevenues !!};
        const monthlyTicketCounts = {!! $monthlyTicketCounts !!};

        // Common Tooltip Configuration
        const chartTooltipConfig = (ticketCountsArray) => ({
            callbacks: {
                label: function(context) {
                    let index = context.dataIndex;
                    let revenue = context.raw;
                    let tickets = ticketCountsArray[index];
                    let formattedRevenue = new Intl.NumberFormat('vi-VN').format(revenue) + 'đ';
                    
                    // Return array to display multiple lines in tooltip
                    return [
                        'Tổng doanh thu: ' + formattedRevenue,
                        'Số vé đã đặt: ' + tickets + ' vé'
                    ];
                }
            }
        });

        // 1. Daily Revenue Chart
        const ctxDaily = document.getElementById('dailyRevenueChart').getContext('2d');
        new Chart(ctxDaily, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: dailyRevenues,
                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: chartTooltipConfig(dailyTicketCounts)
                },
                onClick: (e, activeElements) => {
                    if (activeElements.length > 0) {
                        const index = activeElements[0].index;
                        const clickedDate = dailyDates[index];
                        window.location.href = '/admin/tickets?filter_date=' + clickedDate;
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    }
                }
            }
        });

        // 2. Monthly Revenue Chart
        const ctxMonthly = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: monthlyRevenues,
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: chartTooltipConfig(monthlyTicketCounts)
                },
                onClick: (e, activeElements) => {
                    if (activeElements.length > 0) {
                        const index = activeElements[0].index;
                        const clickedMonth = monthlyDates[index];
                        window.location.href = '/admin/tickets?filter_month=' + clickedMonth;
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection
