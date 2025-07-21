@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-shopping-cart"></i> Penjualan</h1>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Penjualan
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-0">List Penjualan</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="date" id="dateFilter" class="form-control" placeholder="Filter Tanggal">
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="clearFilter" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="salesTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Items</th>
                                <th>Total Qty</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#salesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('sales.data') }}",
            data: function(d) {
                d.date_filter = $('#dateFilter').val();
            }
        },
        columns: [
            {data: 'code', name: 'code'},
            {data: 'sale_date', name: 'sale_date'},
            {data: 'items_count', name: 'items_count', orderable: false, searchable: false},
            {data: 'total_qty', name: 'total_qty', orderable: false, searchable: false},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[1, 'desc']]
    });

    $('#dateFilter').on('change', function() {
        table.draw();
    });

    $('#clearFilter').on('click', function() {
        $('#dateFilter').val('');
        table.draw();
    });
});
</script>
@endpush
