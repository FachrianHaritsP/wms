@extends('layouts.main')

@section('content')

<h2 class="mt-2">Transactions</h2>

<div class="row mt-3">

<!-- FORM -->
<div class="col-md-4">

<div id="reader" style="width:300px;"></div>

<button class="btn btn-secondary mt-2" onclick="startScanner()">Scan QR</button>

<div class="card p-3">

<h5>Transaction Form</h5>

<!--<input type="number" id="product_id" class="form-control mb-2" placeholder="Product ID">
-->
<select id="product_id" class="form-control mb-2">
    <option value="">==- Pilih Produk -==</option>
</select>

<input type="number" id="qty" class="form-control mb-2" placeholder="Qty">

<button class="btn btn-success w-100 mb-2" onclick="stockIn()">Stock In</button>

<button class="btn btn-danger w-100" onclick="stockOut()">Stock Out</button>

</div>

</div>

<!-- HISTORY -->
<div class="col-md-8">

<div class="card p-3">

<h5>Transaction History</h5>

<table class="table">

<thead>
<tr>
<th>Product</th>
<th>Type</th>
<th>Qty</th>
<th>Date</th>
</tr>
</thead>

<tbody id="history_table"></tbody>

</table>

</div>

</div>

</div>

<script src="/js/transactions.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>

@endsection