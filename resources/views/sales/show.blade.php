@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-eye"></i> Detail Penjualan</h1>
            <div>
                @if($sale->status !== 'Sudah Dibayar')
                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endif
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Informasi Penjualan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Kode Penjualan:</strong><br>
                        {{ $sale->code }}
                    </div>
                    <div class="col-md-6">
                        <strong>Tanggal Penjualan:</strong><br>
                        {{ $sale->sale_date->format('d/m/Y') }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ $sale->status === 'Sudah Dibayar' ? 'success' : ($sale->status === 'Belum Dibayar Sepenuhnya' ? 'warning' : 'danger') }}">
                            {{ $sale->status }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Total Amount:</strong><br>
                        <h4 class="text-primary">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Items Penjualan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->saleItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->item->code }}</strong><br>
                                    {{ $item->item->name }}
                                </td>
                                <td>{{ $item->qty }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th colspan="3">Total Keseluruhan</th>
                                <th>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Status Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Total Penjualan:</strong><br>
                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                </div>
                <div class="mb-3">
                    <strong>Sudah Dibayar:</strong><br>
                    Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                </div>
                <div class="mb-3">
                    <strong>Sisa Pembayaran:</strong><br>
                    <span class="text-{{ $sale->remaining_amount > 0 ? 'danger' : 'success' }}">
                        Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}
                    </span>
                </div>
                
                @if($sale->status !== 'Sudah Dibayar')
                <div class="mt-4">
                    <a href="{{ route('payments.create', ['sale_id' => $sale->id]) }}" class="btn btn-success w-100">
                        <i class="fas fa-credit-card"></i> Buat Pembayaran
                    </a>
                </div>
                @endif
            </div>
        </div>

        @if($sale->payments->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5>Riwayat Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->payments as $payment)
                            <tr>
                                <td>
                                    <a href="{{ route('payments.show', $payment->id) }}">
                                        {{ $payment->code }}
                                    </a>
                                </td>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
