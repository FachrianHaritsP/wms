
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
                    <label for="reason" class="form-label">
                        Reason
                    </label>

                    <select id="reason" class="form-select">

                        <option value="">
                            Pilih alasan return
                        </option>

                        <option value="Barang Cacat">
                            Barang Cacat
                        </option>

                        <option value="Barang Tidak Sesuai">
                            Barang Tidak Sesuai
                        </option>

                        <option value="Kemasan Rusak">
                            Kemasan Rusak
                        </option>

                        <option value="Salah Pengiriman">
                            Salah Pengiriman
                        </option>

                        <option value="Lainnya">
                            Lainnya
                        </option>

                    </select>
                </div>

                <div class="mb-3">

                    <label for="notes" class="form-label">
                        Penjelasan
                    </label>

                    <textarea
                        id="notes"
                        class="form-control"
                        rows="3"
                        placeholder="Jelaskan detail alasan return..."></textarea>

                    <small class="text-muted">
                        Jelaskan detail dari alasan yang dipilih.
                        Wajib diisi jika memilih "Lainnya".
                    </small>

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

                                <th>
                                    Qty
                                </th>

                                <th>Reason</th>

                                <th class="d-none d-md-table-cell">Status</th>

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

                                    <td>
                                        {{ $return->qty }}
                                    </td>

                                    <td>
                                        {{ $return->reason }}
                                    </td>

                                    <td class="d-none d-md-table-cell">

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

                                    <td>

                                        <div class="d-flex flex-column gap-1">

                                            <button
                                                class="btn btn-warning btn-sm"
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

                                        </div>

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