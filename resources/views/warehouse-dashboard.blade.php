@extends('layouts.main')

@section('content')
<h2 class="mt-2"> Dashboard</h2>
<!-- Content -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">

    <!-- bungkus -->
    
    <div  class="dashboard-container-fluid">

        <!-- KPI -->
        <div class="container-fluid mt-4", class="card shadow",
        class="table table-striped"> 

            <div class="row text-center">

            <div class="col-md-3">
            <div class="card shadow">
            <div class="card-body">
            <h6>Total Produk</h6>
            <h3 id="total_products"></h3>
            </div>
            </div>
            </div>

            <div class="col-md-3">
            <div class="card shadow">
            <div class="card-body">
            <h6>Total Stok</h6>
            <h3 id="total_stock"></h3>
            </div>
            </div>
            </div>

            <div class="col-md-3">
            <div class="card shadow">
            <div class="card-body">
            <h6>Stock In</h6>
            <h3 id="stock_in"></h3>
            </div>
            </div>
            </div>

            <div class="col-md-3">
            <div class="card shadow">
            <div class="card-body">
            <h6>Stock Out</h6>
            <h3 id="stock_out"></h3>
            </div>
            </div>
            </div>

            </div>
        </div>  

        <!-- Low Stock Table -->
        <div class="container-fluid mt-4">

        <h4>Low Stock Alert</h4>

            <table class="table table-bordered table-striped">

            <thead class="table-danger">
            <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Stock</th>
            </tr>
            </thead>

            <tbody id="low_stock_table">
            </tbody>

            </table>

        </div>

        <!-- Charts -->
        <div class="container-fluid mt-4">

            <div class="row">

                <div class="col-md-6">
                <h5>Product Movement</h5>
                <canvas id="productChart"></canvas>
                </div>

                <div class="col-md-6">
                <h5>Stock Movement</h5>
                <canvas id="stockChart"></canvas>
                </div>

            </div>

        </div>

    </div>
    <!-- end bungkus --->

</div>
<script src="/js/dashboard.js"></script>
@endsection