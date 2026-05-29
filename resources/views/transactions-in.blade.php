@extends('layouts.main')

@section('content')

<h2 class="mt-2">Transactions Scan-in</h2>

<div class="row mt-3">

<!-- FORM -->
<div class="col-md-4">

<div id="reader" style="width:300px;"></div>

<button class="btn btn-secondary mt-2" onclick="startScanner()">Scan QR</button>

<div id="scan-status"></div>

<div class="card p-3">

<h5>Transaction Form</h5>

<!--<input type="number" id="product_id" class="form-control mb-2" placeholder="Product ID">
-->
<select id="product_id" class="form-control mb-2">
    <option value="">==- Pilih Produk -==</option>
</select>

<p>SKU : <span id="product_sku"></span></p>

<p>Size : <span id="product_size"></span></p>

<p>Color : <span id="product_color"></span></p>

<p>Location : <span id="product_location"></span></p>

<p>Stock : <span id="product_stock"></span></p>

<div class="mt-6">
    <label>Jumlah barang</label>
    <input type="number" id="qty" class="form-control mb-2" placeholder="Qty">
</div>


<button class="btn btn-success w-100 mb-2" onclick="stockIn()">Stock In</button>

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
<th>User</th>
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