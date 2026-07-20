@extends('layouts.main')

@section('content')
<!-- Content -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">
    <h2 class="mt-3"> Dashboard</h2>
    <select id="period_filter" onchange="loadDashboard()">
        <option value="today">Hari Ini</option>
        <option value="week">Satu Minggu</option>
        <option value="month">Satu Bulan</option>
    </select>

    <!-- bungkus -->
    
    <div  class="dashboard-container-fluid">

        <!-- KPI -->
        <div class="container-fluid mt-4" class="card shadow" lass="table table-striped"> 

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
            <h6>Stok Masuk</h6>
            <h3 id="stock_in"></h3>
            </div>
            </div>
            </div>

            <div class="col-md-3">
            <div class="card shadow">
            <div class="card-body">
            <h6>Stock Keluar</h6>
            <h3 id="stock_out"></h3>
            </div>
            </div>
            </div>

            </div>
        </div>  

        <!-- Low Stock Table -->
        <div class="container-fluid mt-4">

        <h4>Stok Rendah</h4>

            <table class="table table-bordered table-striped">

            <thead class="table-danger">
            <tr>
            <th>SKU</th>
            <th>Nama</th>
            <th>Stok</th>
            </tr>
            </thead>

            <tbody id="low_stock_table">
            </tbody>

            </table>

        </div>

        <!-- Charts -->
        <div class="container-fluid mt-4">

            <div class="row">

                <!-- Product -->
                <div class="col-md-6">

                    <h5>Pergerakan Produk</h5>

                    <div id="productMovementEmpty"
                        class="text-center text-muted py-5"
                        style="display:none;">
                        Belum ada data pergerakan produk.
                    </div>

                    <canvas id="productChart"></canvas>

                </div>

                <!-- Stock -->
                <div class="col-md-6">

                    <h5>Pergerakan Stok</h5>

                    <div id="stockMovementEmpty"
                        class="text-center text-muted py-5"
                        style="display:none;">
                        Belum ada data pergerakan stok.
                    </div>

                    <canvas id="stockChart"></canvas>

                </div>

            </div>

        </div>
         <!-- endCharts -->

    </div>
    <!-- end bungkus --->

</div>
<script src="/js/dashboard.js"></script>
@endsection