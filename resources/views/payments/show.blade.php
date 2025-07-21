@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-eye"></i> Detail Pembayaran</h1>
            <div>
                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5>Informasi Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Kode Pembayaran:</strong><br>
                        {{ $payment->code }}
                    </div>
                    <div class="col-md-6">
                        <strong>Tanggal Pembayaran:</strong><br>
                        {{ $payment->payment_date->format('d/m/Y') }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Kode Penjualan:</strong><br>
                        <a href="{{ route('sales.show', $payment->sale_id) }}">
                            {{ $payment->sale->code }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <strong>Jumlah Pembayaran:</strong><br>
                        <h4 class="text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h4>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Catatan:</strong><br>
                        {{ $payment->notes ?: 'Tidak ada catatan' }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Total Penjualan:</strong><br>
                                        Rp {{ number_format($payment->sale->total_amount, 0, ',', '.') }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Status Penjualan:</strong><br>
                                        <span class="badge bg-{{ $payment->sale->status === 'Sudah Dibayar' ? 'success' : ($payment->sale->status === 'Belum Dibayar Sepenuhnya' ? 'warning' : 'danger') }}">
                                            {{ $payment->sale->status }}
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Sisa Pembayaran:</strong><br>
                                        <span class="text-{{ $payment->sale->remaining_amount > 0 ? 'danger' : 'success' }}">
                                            Rp {{ number_format($payment->sale->remaining_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <form method="POST" action="{{ route('payments.destroy', $payment->id) }}" 
                              class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Hapus Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection