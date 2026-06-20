@extends('layouts.main')

@section('content')

<meta
    name="user-role"
    content="{{ Auth::user()->role }}">
<meta name="transaction-type" content="out">

<h2 class="mt-2">Transactions Scan-out</h2>

<div class="row mt-3">

    <!-- FORM -->
    <div class="col-12 col-lg-4 mb-3">

        <div id="reader" style="width:300px;">
            
        </div>

        <button class="btn btn-secondary mb-2" onclick="startScanner()">Scan QR</button>

        <button  class="btn btn-primary mb-2" onclick="updateTransaction()">

            Test Update

        </button>

        <div class="card p-3">

            <h5>Transaction Form</h5>

            <!--<input type="number" id="product_id" class="form-control mb-2" placeholder="Product ID">
            -->
            <input type="hidden" id="transaction_id">
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

            <button class="btn btn-danger w-100" onclick="stockOut()">Stock Out</button>

        </div>

    </div>

    <!-- HISTORY -->
    <div class="col-12 col-lg-8 mb-3">
        <div class="mb-3">

            <label class="form-label">
                Filter Tanggal
            </label>

            <input
                type="date"
                id="filter_date"
                class="form-control">

        </div>

        <div class="card p-3">

            <h5>Transaction History</h5>

            <div col-12 col-lg-8>

                <table class="table table-sm">

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>user</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="history_table"></tbody>

                </table>
            </div>
        </div>

    </div>

</div>

<script src="/js/transactions.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>

@endsection