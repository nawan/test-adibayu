@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-edit"></i> Edit Pembayaran</h1>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Kode Pembayaran</label>
                            <input type="text" class="form-control" id="code" value="{{ $payment->code }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="sale_id" class="form-label">Penjualan</label>
                            <input type="text" class="form-control" value="{{ $payment->sale->code }}" readonly>
                            <input type="hidden" name="sale_id" value="{{ $payment->sale_id }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Total Penjualan:</strong><br>
                                            <span id="totalAmount">Rp {{ number_format($payment->sale->total_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Sudah Dibayar:</strong><br>
                                            <span id="paidAmount">Rp {{ number_format($payment->sale->paid_amount - $payment->amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Sisa Pembayaran:</strong><br>
                                            <span id="remainingAmount" class="text-danger">Rp {{ number_format($payment->sale->total_amount - ($payment->sale->paid_amount - $payment->amount), 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="payment_date" class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Jumlah Pembayaran <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" step="0.01" min="0.01" 
                                       max="{{ $payment->sale->total_amount - ($payment->sale->paid_amount - $payment->amount) }}"
                                       value="{{ old('amount', $payment->amount) }}" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Pembayaran
                            </button>
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection