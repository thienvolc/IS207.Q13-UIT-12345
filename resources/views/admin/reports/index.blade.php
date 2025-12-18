@extends('layouts.admin')

@section('title', 'Báo cáo & Thống kê - Admin')
@section('page-title', 'Báo cáo & Thống kê')

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Báo cáo hiệu quả kinh doanh</h1>
                <p class="page-subtitle">Theo dõi doanh thu, sản phẩm bán chạy và tăng trưởng.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export.revenue-pdf') }}" class="btn btn-outline-primary" target="_blank">
                    <i class="fa fa-file-pdf me-2"></i>Xuất PDF
                </a>
                <select class="form-select" id="timePeriod" style="min-width: 150px;">
                    <option value="7">7 ngày qua</option>
                    <option value="30" selected>30 ngày qua</option>
                    <option value="90">3 tháng qua</option>
                    <option value="365">1 năm qua</option>
                </select>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="revenue-tab" data-bs-toggle="tab" data-bs-target="#revenue"
                    type="button" role="tab" aria-controls="revenue" aria-selected="true">
                    <i class="fa fa-chart-area me-2"></i>Doanh thu
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button"
                    role="tab" aria-controls="products" aria-selected="false">
                    <i class="fa fa-box me-2"></i>Sản phẩm
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers" type="button"
                    role="tab" aria-controls="customers" aria-selected="false">
                    <i class="fa fa-users me-2"></i>Khách hàng
                </button>
            </li>
        </ul>

        <div class="tab-content" id="reportTabsContent">

            {{-- Tab 1: Revenue --}}
            <div class="tab-pane fade show active" id="revenue" role="tabpanel" aria-labelledby="revenue-tab">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header border-bottom-0 pb-0 d-flex justify-content-between">
                                <h5 class="card-title mb-0">Biểu đồ Doanh thu</h5>
                                <div class="h5 mb-0 text-success fw-bold" id="totalRevenueDisplay">-</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="height: 400px;">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Revenue Summary Cards --}}
                    <div class="col-md-4">
                        <div class="card text-center h-100">
                            <div class="card-body py-4">
                                <div class="avatar-md bg-primary-soft text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px; min-width: 64px;">
                                    <i class="fa fa-dollar-sign fs-4"></i>
                                </div>
                                <h3 class="fw-bold mb-1" id="sumRevenue">-</h3>
                                <div class="text-muted">Tổng doanh thu</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center h-100">
                            <div class="card-body py-4">
                                <div class="avatar-md bg-primary-soft text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px; min-width: 64px;">
                                    <i class="fa fa-shopping-bag fs-4"></i>
                                </div>
                                <h3 class="fw-bold mb-1" id="sumOrders">-</h3>
                                <div class="text-muted">Đơn hàng hoàn thành</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center h-100">
                            <div class="card-body py-4">
                                <div class="avatar-md bg-primary-soft text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px; min-width: 64px;">
                                    <i class="fa fa-chart-line fs-4"></i>
                                </div>
                                <h3 class="fw-bold mb-1" id="avgOrderValue">-</h3>
                                <div class="text-muted">Giá trị đơn trung bình</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Products --}}
            <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔥 Top 10 Sản phẩm bán chạy nhất</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Sản phẩm</th>
                                        <th style="width: 150px;">Giá bán</th>
                                        <th style="width: 250px;">Mức độ phổ biến</th>
                                        <th class="text-end" style="width: 120px;">Đã bán</th>
                                        <th class="text-end" style="width: 150px;">Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody id="topProductsTable">
                                    {{-- Loaded via JS --}}
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 3: Customers --}}
            <div class="tab-pane fade" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header border-bottom-0 pb-0">
                                <h5 class="card-title mb-0 text-center">Phân loại Khách hàng (trong kỳ)</h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container position-relative" style="height: 350px;">
                                    <canvas id="customerChart"></canvas>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="row">
                                        <div class="col-6 border-end">
                                            <h4 class="fw-bold mb-0 text-primary" id="newCustomersCount">-</h4>
                                            <small class="text-muted">Khách hàng mới</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="fw-bold mb-0 text-info" id="returningCustomersCount">-</h4>
                                            <small class="text-muted">Khách quay lại</small>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const periodSelect = document.getElementById('timePeriod');
            let revenueChart = null;
            let customerChart = null;

            // --- FETCH & RENDER FUNCTIONS ---

            // 1. Revenue
            function fetchRevenueData(days) {
                fetch(`{{ route('admin.reports.revenue') }}?days=${days}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        updateRevenueChart(data);
                        updateRevenueSummary(data);
                    })
                    .catch(error => console.error('Error loading revenue:', error));
            }

            function updateRevenueSummary(data) {
                const totalRev = data.revenue.reduce((a, b) => a + b, 0);
                const totalOrd = data.orders.reduce((a, b) => a + b, 0);
                const avgVal = totalOrd > 0 ? totalRev / totalOrd : 0;

                const formatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });

                document.getElementById('sumRevenue').innerText = formatter.format(totalRev);
                document.getElementById('totalRevenueDisplay').innerText = formatter.format(totalRev);
                document.getElementById('sumOrders').innerText = totalOrd;
                document.getElementById('avgOrderValue').innerText = formatter.format(avgVal);
            }

            function updateRevenueChart(data) {
                const ctx = document.getElementById('revenueChart');
                if (revenueChart) revenueChart.destroy();

                revenueChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Doanh thu',
                            data: data.revenue,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y'
                        }, {
                            label: 'Đơn hàng',
                            data: data.orders,
                            borderColor: '#198754',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            y: {
                                type: 'linear', display: true, position: 'left', beginAtZero: true,
                                ticks: { callback: value => new Intl.NumberFormat('mm', { notation: "compact" }).format(value) }
                            },
                            y1: {
                                type: 'linear', display: true, position: 'right', beginAtZero: true,
                                grid: { drawOnChartArea: false }
                            }
                        }
                    }
                });
            }

            // 2. Products
            function fetchTopProducts(days) {
                const tbody = document.getElementById('topProductsTable');
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

                fetch(`{{ route('admin.reports.top-products') }}?limit=10&days=${days}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to load products');
                        return response.json();
                    })
                    .then(data => {
                        const tbody = document.getElementById('topProductsTable');
                        tbody.innerHTML = '';

                        if (data.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-5">Chưa có dữ liệu</td></tr>';
                            return;
                        }

                        const formatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });

                        data.forEach((item, index) => {
                            const tr = document.createElement('tr');
                            const price = formatter.format(item.price || 0);
                            const revenue = formatter.format(item.total_revenue || 0);

                            tr.innerHTML = `
                                        <td><span class="fw-bold text-muted">${index + 1}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="${item.thumb || '/images/no-image.png'}" class="rounded me-3 border" width="48" height="48" style="object-fit:cover" onerror="this.src='https://via.placeholder.com/48'">
                                                <div>
                                                    <div class="fw-medium text-truncate" style="max-width: 250px;" title="${item.title}">${item.title}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${price}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: ${item.percentage}%" aria-valuenow="${item.percentage}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ms-2 small text-muted">${item.percentage}%</span>
                                            </div>
                                        </                    td>
                                        <td class="text-end fw-bold">${item.total_sold}</td>
                                        <td class="text-end text-success fw-bold">${revenue}</td>
                                    `;
                            tbody.appendChild(tr);
                        });
                    })
                    .catch(error => {
                        console.error(error);
                        document.getElementById('topProductsTable').innerHTML = `<tr><td colspan="6" class="text-center text-danger py-5">Lỗi tải dữ liệu: ${error.message}</td></tr>`;
                    });
            }

            // 3. Customers
            function fetchCustomerData(days) {
                fetch(`{{ route('admin.reports.customers') }}?days=${days}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to load customer data');
                        return response.json();
                    })
                    .then(data => {
                        updateCustomerChart(data);
                        document.getElementById('newCustomersCount').innerText = data.data[0];
                        document.getElementById('returningCustomersCount').innerText = data.data[1];
                    })
                    .catch(error => console.error('Error loading customers:', error));
            }

            function updateCustomerChart(data) {
                const ctx = document.getElementById('customerChart');
                if (customerChart) customerChart.destroy();

                customerChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data,
                            backgroundColor: ['#0d6efd', '#0dcaf0'],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } }
                        }
                    }
                });
            }

            // --- INITIALIZATION ---

            function loadAllData(days) {
                fetchRevenueData(days);
                // Products might not depend on days yet in backend but good to structure it
                fetchTopProducts(days);
                fetchCustomerData(days);
            }

            loadAllData(30); // Default

            if (periodSelect) {
                periodSelect.addEventListener('change', function () {
                    loadAllData(this.value);
                });
            }
        });
    </script>
@endpush