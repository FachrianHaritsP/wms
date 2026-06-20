@extends('layouts.main')

@section('content')

<h3 id="returnsTitle">Create Return</h3>

<div class="row mt-3">
    <!-- Card create return -->
    <div class="col-12 col-lg-4 mb-3">
        <div class="card p-3" >

            <div class="mb-3">
                <label>Product ID</label>

                <select id="product_id">
                    <option value="">
                        Pilih Produk
                    </option>
                     @foreach($products as $product)

                        <option value="{{ $product->id }}">

                            {{ $product->name }}

                        </option>

                    @endforeach
                </select>
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

            <button id="submitBtn" onclick="submitReturn()" class="btn btn-primary w-100">
                Submit Return
            </button>

        </div>
    </div>

    <!-- History -->
    <div class="col-12 col-lg-8">
        <div class="card p-3">
            <h5>Return History</h5>
            <div class="table-responsive">
                <table class="table table-sm" id="returnTable">

                    <thead>

                        <tr class="bg-gray-200">

                            <th class="border px-2 py-2">Product</th>
                            <th class="border px-2 py-2 d-none d-md-table-cell">Qty</th>
                            <th class="border px-2 py-2">Reason</th>
                            <th class="border px-2 py-2">Status</th>
                            <th class="border px-2 py-2 d-none d-md-table-cell">User</th>
                            <th class="border px-2 py-2">Action</th>
                
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($returns as $return)

                        <tr>

                            <td class="border px-2 py-2">
                                {{ $return->product->name }}
                            </td>

                            <td class="border px-1 py-1">
                                {{ $return->qty }}
                            </td>

                            <td class="border px-2 py-2">
                                {{ $return->reason }}
                            </td>

                            <td class="border px-1 py-1">

                                @if($return->status == 'pending')

                                    <span class="bg-yellow-200 px-1 py-1 rounded">
                                        Pending
                                    </span>

                                @endif
                                @if($return->status == 'rejected')

                                    <span class="bg-red-200 px-1 py-1 rounded">
                                        Rejected
                                    </span>

                                @endif
                                @if($return->status == 'approved')

                                    <span class="bg-green-200 px-1 py-1 rounded">
                                        Approved
                                    </span>

                                @endif

                            </td>

                            <td class="border px-2 py-2 d-none d-md-table-cell">
                                {{ $return->user->name }}
                            </td>

                            <td>

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
                                    class="btn btn-danger btn-sm" onclick="cancelReturn({{ $return->id }})">
                                    Cancel
                                </button>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>
        
        </div>
    
    </div>

</div><!-- end-div-row-->

<script src="/js/returns.js"></script>

@endsection