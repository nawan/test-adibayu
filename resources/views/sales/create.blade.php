@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-plus"></i> Tambah Penjualan</h1>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sale_date" class="form-label">Tanggal Penjualan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('sale_date') is-invalid @enderror" 
                                   id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                            @error('sale_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Items Penjualan</h5>
                        <button type="button" class="btn btn-success btn-sm" id="addItem">
                            <i class="fas fa-plus"></i> Tambah Item
                        </button>
                    </div>

                    <div id="itemsContainer">
                        <div class="item-row mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Item <span class="text-danger">*</span></label>
                                    <select name="items[0][item_id]" class="form-select item-select" required>
                                        <option value="">Pilih Item</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                                {{ $item->code }} - {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Qty <span class="text-danger">*</span></label>
                                    <input type="number" name="items[0][qty]" class="form-control qty-input" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Harga <span class="text-danger">*</span></label>
                                    <input type="number" name="items[0][price]" class="form-control price-input" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Total</label>
                                    <input type="text" class="form-control total-price" readonly>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-item d-block">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Total Keseluruhan: <span id="grandTotal">Rp 0</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Penjualan
                            </button>
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
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
let itemIndex = 1;

$(document).ready(function() {
    // Add item row
    $('#addItem').click(function() {
        const newRow = `
            <div class="item-row mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Item <span class="text-danger">*</span></label>
                        <select name="items[${itemIndex}][item_id]" class="form-select item-select" required>
                            <option value="">Pilih Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                    {{ $item->code }} - {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Qty <span class="text-danger">*</span></label>
                        <input type="number" name="items[${itemIndex}][qty]" class="form-control qty-input" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="items[${itemIndex}][price]" class="form-control price-input" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control total-price" readonly>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm remove-item d-block">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#itemsContainer').append(newRow);
        itemIndex++;
    });

    // Remove item row
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            calculateGrandTotal();
        }
    });

    // Auto fill price when item selected
    $(document).on('change', '.item-select', function() {
        const price = $(this).find(':selected').data('price');
        $(this).closest('.row').find('.price-input').val(price);
        calculateRowTotal($(this).closest('.item-row'));
    });

    // Calculate row total when qty or price changes
    $(document).on('input', '.qty-input, .price-input', function() {
        calculateRowTotal($(this).closest('.item-row'));
    });

    function calculateRowTotal(row) {
        const qty = parseFloat(row.find('.qty-input').val()) || 0;
        const price = parseFloat(row.find('.price-input').val()) || 0;
        const total = qty * price;
        
        row.find('.total-price').val('Rp ' + total.toLocaleString('id-ID'));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.item-row').each(function() {
            const qty = parseFloat($(this).find('.qty-input').val()) || 0;
            const price = parseFloat($(this).find('.price-input').val()) || 0;
            grandTotal += qty * price;
        });
        $('#grandTotal').text('Rp ' + grandTotal.toLocaleString('id-ID'));
    }
});
</script>
@endpush