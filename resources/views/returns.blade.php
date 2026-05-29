@extends('layouts.main')

@section('content')

<h3>Create Return</h3>

<div class="row mt-3">
    <!-- Card create return -->
    <div class="card p-3" style="max-width: 400px;">

        <div class="mb-3">
            <label>Product ID</label>
            <input type="number" id="product_id" class="form-control" value="1">
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

        <button onclick="submitReturn()" class="btn btn-primary">
            Submit Return
        </button>

    </div>


    <!-- History -->
    <div class="col-md-8">
        <div class="card p-3">
            <table class="table-auto w-full border mt-4" id="returnTable">

        <thead>

            <tr class="bg-gray-200">

                <th class="border px-2 py-2">Product</th>
                <th class="border px-2 py-2">Qty</th>
                <th class="border px-2 py-2">Reason</th>
                <th class="border px-2 py-2">Status</th>
                <th class="border px-2 py-2">User</th>
    
            </tr>

        </thead>

        <tbody>

            @foreach($returns as $return)

            <tr>

                <td class="border px-2 py-2">
                    {{ $return->product->name }}
                </td>

                <td class="border px-2 py-2">
                    {{ $return->qty }}
                </td>

                <td class="border px-2 py-2">
                    {{ $return->reason }}
                </td>

                <td class="border px-2 py-2">

                    @if($return->status == 'pending')

                        <span class="bg-yellow-200 px-2 py-1 rounded">
                            Pending
                        </span>

                    @endif

                </td>

                <td class="border px-2 py-2">
                    {{ $return->user->name }}
                </td>

            </tr>

            @endforeach

        </tbody>

        </table>
        </div>
    
    </div>


</div><!-- end-div-row-->

<script src="/js/returns.js"></script>

@endsection