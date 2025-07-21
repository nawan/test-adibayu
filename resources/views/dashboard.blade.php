@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        </div>
    </div>
</div>

<!-- Date Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Widgets -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ number_format($totalTransactions) }}</h4>
                        <p class="mb-0">Total Transaksi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                        <p class="mb-0">Total Penjualan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ number_format($totalQty) }}</h4>
                        <p class="mb-0">Total Qty Terjual</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line"></i> Penjualan per Bulan</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar"></i> Item Terlaris</h5>
            </div>
            <div class="card-body">
                <canvas id="itemsSoldChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Monthly Sales Chart
const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
const monthlySalesData = @json($monthlySales);

const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const monthlyLabels = monthlySalesData.map(item => monthNames[item.month - 1] + ' ' + item.year);
const monthlyValues = monthlySalesData.map(item => item.total);

new Chart(monthlySalesCtx, {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Penjualan (Rp)',
            data: monthlyValues,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

// Items Sold Chart
const itemsSoldCtx = document.getElementById('itemsSoldChart').getContext('2d');
const itemsSoldData = @json($itemsSold);

const itemLabels = itemsSoldData.map(item => item.name);
const itemValues = itemsSoldData.map(item => item.total_qty);

new Chart(itemsSoldCtx, {
    type: 'bar',
    data: {
        labels: itemLabels,
        datasets: [{
            label: 'Qty Terjual',
            data: itemValues,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
