@extends('layouts.main')

@section('content')

<h3>Test Return</h3>

<div class="card p-3" style="max-width: 400px;">

    <div class="mb-3">
        <label>Product ID</label>
        <input type="number" id="product_id" class="form-control" value="1">
    </div>

    <div class="mb-3">
        <label>Qty</label>
        <input type="number" id="qty" class="form-control" value="1">
    </div>

    <div class="mb-3">
        <label>Reason</label>
        <textarea id="reason" class="form-control">Barang cacat</textarea>
    </div>

    <div class="mb-3">
        <label>Notes</label>
        <textarea id="notes" class="form-control"></textarea>
    </div>

    <button onclick="submitReturn()" class="btn btn-primary">
        Submit Return
    </button>

</div>

<script src="/js/returns.js"></script>

@endsection