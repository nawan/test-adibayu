@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-credit-card"></i> Pembayaran</h1>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pembayaran
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
                        <h5 class="mb-0">List Pembayaran</h5>
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
                    <table id="paymentsTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Kode Penjualan</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
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
    var table = $('#paymentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('payments.data') }}",
            data: function(d) {
                d.date_filter = $('#dateFilter').val();
            }
        },
        columns: [
            {data: 'code', name: 'code'},
            {data: 'sale_code', name: 'sale_code'},
            {data: 'payment_date', name: 'payment_date'},
            {data: 'amount', name: 'amount'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[2, 'desc']]
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