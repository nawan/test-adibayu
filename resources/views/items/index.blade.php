@extends('layouts.app')

@section('title', 'Items')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-box"></i> Items</h1>
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Item
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">List Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="itemsTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Image</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stock</th>
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
    $('#itemsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('items.data') }}",
        columns: [
            {data: 'code', name: 'code'},
            {data: 'image', name: 'image'},
            {data: 'name', name: 'name'},
            {data: 'price', name: 'price'},
            {data: 'stock', name: 'stock'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });
});
</script>
@endpush