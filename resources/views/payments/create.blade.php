@extends('layouts.app')

@section('title', 'Tambah Pembayaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-plus"></i> Tambah Pembayaran</h1>
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
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sale_id" class="form-label">Penjualan <span class="text-danger">*</span></label>
                            <select class="form-select @error('sale_id') is-invalid @enderror" id="sale_id" name="sale_id" required>
                                <option value="">Pilih Penjualan</option>
                                @foreach($sales as $sale)
                                    <option value="{{ $sale->id }}" {{ old('sale_id', request('sale_id')) == $sale->id ? 'selected' : '' }}>
                                        {{ $sale->code }} - Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sale_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="payment_date" class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Total Penjualan:</strong><br>
                                            <span id="totalAmount">Rp 0</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Sudah Dibayar:</strong><br>
                                            <span id="paidAmount">Rp 0</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Sisa Pembayaran:</strong><br>
                                            <span id="remainingAmount" class="text-danger">Rp 0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Jumlah Pembayaran <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}" required>
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
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Pembayaran
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

@push('scripts')
<script>
$(document).ready(function() {
    // Get sale details when sale is selected
    $('#sale_id').change(function() {
        const saleId = $(this).val();
        if (saleId) {
            $.ajax({
                url: `/payments/get-sale-details/${saleId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#totalAmount').text('Rp ' + parseFloat(data.total_amount).toLocaleString('id-ID'));
                    $('#paidAmount').text('Rp ' + parseFloat(data.paid_amount).toLocaleString('id-ID'));
                    $('#remainingAmount').text('Rp ' + parseFloat(data.remaining_amount).toLocaleString('id-ID'));
                    
                    // Set max amount to remaining amount
                    $('#amount').attr('max', data.remaining_amount);
                    
                    // Set default amount to remaining amount
                    $('#amount').val(data.remaining_amount);
                }
            });
        } else {
            $('#totalAmount').text('Rp 0');
            $('#paidAmount').text('Rp 0');
            $('#remainingAmount').text('Rp 0');
            $('#amount').attr('max', '');
            $('#amount').val('');
        }
    });

    // Trigger change if sale_id is already selected (e.g. from URL parameter)
    if ($('#sale_id').val()) {
        $('#sale_id').trigger('change');
    }
});
</script>
@endpush