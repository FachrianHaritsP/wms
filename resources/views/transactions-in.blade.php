@extends('layouts.main')

@section('content')

<meta
    name="user-role"
    content="{{ Auth::user()->role }}">
<meta name="transaction-type" content="in">

<h2 class="mt-2">Transactions Scan-in</h2>

<div class="row mt-3">

    <!-- FORM -->
    <div class="col-12 col-lg-4 mb-3">

        <div id="reader" class="w-100"></div>

        <button class="btn btn-secondary mb-2" onclick="startScanner()">Scan QR</button>

        <button  class="btn btn-primary mb-2" onclick="updateTransaction()">

            Test Update

        </button>

        <div id="scan-status"></div>

        <div class="card p-3">

            <h5>Transaction Form</h5>
            <input type="hidden" id="transaction_id" value="">

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
            <div class="table-responsive">
                
                <table class="table table-sm">

                    <thead>
                        <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>User</th>
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