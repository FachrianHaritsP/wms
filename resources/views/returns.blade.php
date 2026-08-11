
@extends('layouts.main')

@section('content')

<div class="container-fluid">

    <h2 class="mt-2">Create Return</h2>

    <div class="row mt-3">

        <!-- Create Return -->
        <div class="col-12 col-lg-4 mb-3">

            <div class="card p-3">

                <div class="mb-3">
                    <label>Product</label>

                    <select
                        id="product_id"
                        class="form-select"
                        {{ $products->isEmpty() ? 'disabled' : '' }}>

                        @if($products->isEmpty())

                            <option value="">
                                Belum ada produk
                            </option>

                        @else

                            <option value="">
                                Pilih Produk
                            </option>

                            @foreach($products as $product)

                                <option value="{{ $product->id }}">
                                    {{ $product->name }}
                                </option>

                            @endforeach

                        @endif

                    </select>
                </div>

                <div class="mb-3">
                    <label>Qty</label>
                    <input
                        type="number"
                        id="qty"
                        class="form-control"
                        value="1">
                </div>

                <div class="mb-3">
                    <label>Reason</label>
                    <textarea
                        id="reason"
                        class="form-control">Barang cacat</textarea>
                </div>

                <div class="mb-3">
                    <label>Notes</label>
                    <textarea
                        id="notes"
                        class="form-control"></textarea>
                </div>

                <button
                    id="submitBtn"
                    onclick="submitReturn()"
                    class="btn btn-primary w-100"
                    {{ $products->isEmpty() ? 'disabled' : '' }}>
                    Submit Return
                </button>

            </div>

        </div>


        <!-- Return History -->
        <div class="col-12 col-lg-8">

            <div class="card p-3">

                <h5>Return History</h5>

                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-sm small">

                        <thead class="table-dark">

                            <tr>

                                <th>Product</th>

                                <th class="d-none d-md-table-cell">
                                    Qty
                                </th>

                                <th>Reason</th>

                                <th>Status</th>

                                <th class="d-none d-md-table-cell">
                                    User
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody id="returnTable">

                            @forelse($returns as $return)

                                <tr>

                                    <td>
                                        {{ $return->product->name }}
                                    </td>

                                    <td class="d-none d-md-table-cell">
                                        {{ $return->qty }}
                                    </td>

                                    <td>
                                        {{ $return->reason }}
                                    </td>

                                    <td>

                                        @if($return->status == 'pending')

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                        @elseif($return->status == 'rejected')

                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>

                                        @elseif($return->status == 'approved')

                                            <span class="badge bg-success">
                                                Approved
                                            </span>

                                        @endif

                                    </td>

                                    <td class="d-none d-md-table-cell">
                                        {{ $return->user->name }}
                                    </td>

                                    <td class="text-nowrap">

                                        <button
                                            class="btn btn-warning btn-sm me-1"
                                            onclick="editReturn(
                                                {{ $return->id }},
                                                {{ $return->product_id }},
                                                {{ $return->qty }},
                                                '{{ $return->reason }}',
                                                '{{ $return->notes }}'
                                            )">
                                            Edit
                                        </button>

                                        <button
                                            class="btn btn-danger btn-sm"
                                            onclick="cancelReturn({{ $return->id }})">
                                            Cancel
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center text-muted py-3">

                                        Belum ada data return.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="/js/returns.js"></script>

@endsection