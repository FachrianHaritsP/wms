@extends('layouts.main')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">
        Stock Movement Report
    </h2>

    {{-- filter  date --}}
    <div class="row mb-3">

        <div class="col-md-4 mb-2">

            <input type="date"
                class="form-control">

        </div>

        <div class="col-md-4 mb-2">

            <input type="date"
                class="form-control">

        </div>

        <div class="col-md-4 mb-2">

            <button class="btn btn-primary w-100">

                Filter

            </button>

        </div>

    </div>

    {{-- KPI --}}
    <div class="row mb-4">

        <div class="col-12 col-md-4 mb-3">

            <div class="card shadow p-3">

                <h6>Total Stock In</h6>

                <h3>
                    {{ $totalStockIn }}
                </h3>

            </div>

        </div>

        <div class="col-12 col-md-4 mb-3">

            <div class="card shadow p-3">

                <h6>Total Stock Out</h6>

                <h3>
                    {{ $totalStockOut }}
                </h3>

            </div>

        </div>

        <div class="col-12 col-md-4 mb-3">

            <div class="card shadow p-3">

                <h6>Total Transactions</h6>

                <h3>
                    {{ $totalTransactions }}
                </h3>

            </div>

        </div>

    </div>

    {{-- Product Movement --}}
    <div class="card shadow p-3 mb-4">

    <h5 class="mb-3">

        Top Product Movement

    </h5>

    <div class="table-responsive">

        <table class="table table-bordered table-sm">

            <thead class="table-primary">

                <tr>

                    <th>Product</th>

                    <th>Total In</th>

                    <th>Total Out</th>

                </tr>

            </thead>

            <tbody>

                @foreach($topMovements as $item)

                <tr>

                    <td>

                        {{ $item->product->name }}

                    </td>

                    <td>

                        <span class="badge bg-success">

                            {{ $item->total_in }}

                        </span>

                    </td>

                    <td>

                        <span class="badge bg-danger">

                            {{ $item->total_out }}

                        </span>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        </div>

    </div>

    {{-- Low Stock --}}
    <div class="card shadow p-3 mb-4">

        <h5 class="mb-3">

            Low Stock Alert

        </h5>

        <div class="table-responsive">

            <table class="table table-bordered table-sm">

                <thead class="table-danger">

                    <tr>

                        <th>Product</th>

                        <th>Stock</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($lowStocks as $item)

                    <tr>

                        <td>

                            {{ $item->name }}

                        </td>

                        <td>

                            <span class="badge bg-danger">

                                {{ $item->stock }}

                            </span>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="2"
                            class="text-center">

                            No Low Stock

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- return-report --}}
    <div class="card shadow p-3 mb-4">

        <h5 class="mb-3">

            Return Summary

        </h5>

        <div class="table-responsive">

            <table class="table table-bordered table-sm">

                <thead class="table-warning">

                    <tr>

                        <th>Status</th>

                        <th>Total</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>

                            <span class="badge bg-success">

                                Approved

                            </span>

                        </td>

                        <td>

                            {{ $returnApproved }}

                        </td>

                    </tr>

                    <tr>

                        <td>

                            <span class="badge bg-warning text-dark">

                                Pending

                            </span>

                        </td>

                        <td>

                            {{ $returnPending }}

                        </td>

                    </tr>

                    <tr>

                        <td>

                            <span class="badge bg-danger">

                                Rejected

                            </span>

                        </td>

                        <td>

                            {{ $returnRejected }}

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    {{-- STockOpname - Report --}}
    <div class="card shadow p-3 mb-4">

        <h5 class="mb-3">
            Stock Opname Summary
        </h5>

        <div class="table-responsive">

            <table class="table table-bordered table-sm">

                <thead class="table-info">

                    <tr>

                        <th>Session Code</th>
                        <th>Match</th>
                        <th>Discrepancy</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($opnameSummary as $item)

                    <tr>

                        <td>
                            {{ $item->session_code }}
                        </td>

                        <td>

                            <span class="badge bg-success">

                                {{ $item->total_match }}

                            </span>

                        </td>

                        <td>

                            <span class="badge bg-danger">

                                {{ $item->total_discrepancy }}

                            </span>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="3"
                            class="text-center">

                            No Opname Session

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card shadow p-3">

        <h5 class="mb-3">
            Transaction History
        </h5>

        <div class="table-responsive">

            <table class="table table-bordered table-striped table-sm">

                <thead class="table-dark">

                    <tr>

                        <th>Product</th>

                        <th>Type</th>

                        <th>Qty</th>

                        <th class="d-none d-md-table-cell">
                            Date
                        </th>

                        <th class="d-none d-md-table-cell">
                            User
                        </th>

                    </tr>

                </thead>

                <tbody> 

                    @forelse($transactions as $transaction)

                    <tr>

                        <td>

                            {{ $transaction->product->name }}

                        </td>

                        <td>

                            @if($transaction->type == 'in')

                                <span class="badge bg-success">

                                    Stock In

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Stock Out

                                </span>

                            @endif

                        </td>

                        <td>

                            {{ $transaction->qty }}

                        </td>

                        <td class="d-none d-md-table-cell">

                            {{-- {{ $transaction->created_at }} --}}
                            {{ $transaction->created_at->format('d M Y H:i') }}

                        </td>

                        <td class="d-none d-md-table-cell">

                            {{ $transaction->user->name }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center">

                            No Data

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>
            <div class="mt-3">

                {{ $transactions->links() }}

            </div>

        </div>

    </div>

</div>

@endsection