@extends('layouts.main')

@section('content')

<div class="container">

    <h3 class="mb-4 font-bold text-xl">
        Stock Opname
    </h3>

    <div class="row">

        {{-- Scan Card --}}
        <div class="col-md-4">
            <div class="mb-4">        
                    {{-- <span class="bg-blue-200 px-3 py-2 rounded">

                        Items Checked :
                        {{ $totalChecked }} <!-- klo dari db -->

                    </span> --}}
                    <span id="counter" class="bg-blue-200 px-3 py-2 rounded">Items Checked : 0</span>
            </div>
            <div class="mb-3">
                <button onclick="startOpname()" class="btn btn-primary">
                    Start Opname
                </button>
            </div>   
            <div id="opnameForm" style="display:none;">
                <div class="card p-3">
                    <div class="mb-3">
                        <label class="mb-2">
                        Scan QR
                        </label>
                        
                        <input type="text" id="scanner" class="form-control" placeholder="Scan QR Product">
                        <input type="hidden" id="product_id">
                        <button onclick="scanProduct()" class="btn btn-success mt-3">

                            Search Product

                        </button>
                    </div>
                    <div class="mb-3">
                        <button onclick="openCamera()" class="btn btn-success mt-2">
                            Open Camera
                        </button>
                    </div>
                    <div id="reader"class="mt-3">
                    </div>
                    
                    <hr>

                    <p>
                        SKU :
                        <span id="product_sku">-</span>
                    </p>

                    <p>
                        Name :
                        <span id="product_name">-</span>
                    </p>

                    <p>
                        System Stock :
                        <span id="product_stock">-</span>
                    </p>

                    <p>
                        Location :
                        <span id="product_location">-</span>
                    </p>

                    <label class="mt-3 mb-2">
                        Physical Stock
                    </label>

                    <input type="number"
                        id="physical_stock"
                        class="form-control">

                    <button onclick="saveOpname()"
                            class="btn btn-primary mt-3">

                        Save Opname

                    </button>

                </div>
            </div>

        </div>

        {{-- History --}}
        <div class="col-md-8">

            {{-- History Opname --}}
            <div class="card p-3">

                <h5 class="font-bold mb-3">
                    History Stock Opname
                </h5>

                <table class="table-auto w-full border mt-4">

                    <thead>

                        <tr class="bg-gray-200">

                            <th class="border px-2 py-2">
                                Product
                            </th>

                            <th class="border px-2 py-2">
                                System
                            </th>

                            <th class="border px-2 py-2">
                                Physical
                            </th>

                            <th class="border px-2 py-2">
                                Difference
                            </th>

                            <th class="border px-2 py-2">
                                Status
                            </th>

                            <th class="border px-2 py-2">
                                User
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($opnames as $opname)

                        <tr>

                            <td class="border px-2 py-2">
                                {{ $opname->product->name }}
                            </td>

                            <td class="border px-2 py-2">
                                {{ $opname->system_stock }}
                            </td>

                            <td class="border px-2 py-2">
                                {{ $opname->physical_stock }}
                            </td>

                            <td class="border px-2 py-2">
                                {{ $opname->difference }}
                            </td>

                            <td class="border px-2 py-2">

                                @if($opname->status == 'match')

                                    <span class="bg-green-200 px-2 py-1 rounded">

                                        Match

                                    </span>

                                @else

                                    <span class="bg-red-200 px-2 py-1 rounded">

                                        Discrepancy

                                    </span>

                                @endif

                            </td>

                            <td class="border px-2 py-2">
                                {{ $opname->user->name }}
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

                <div class="mt-4">

                    {{ $opnames->links() }}

                </div>

            </div>

            {{-- Related Returns --}}
            <div class="card p-3 mt-4">

                <h5 class="font-bold mb-3">

                    Related Returns

                </h5>

                <table class="table-auto w-full border">

                    <thead>

                        <tr class="bg-gray-200">

                            <th class="border px-2 py-2">
                                Product
                            </th>

                            <th class="border px-2 py-2">
                                Qty
                            </th>

                            <th class="border px-2 py-2">
                                Reason
                            </th>

                            <th class="border px-2 py-2">
                                Status
                            </th>

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

                                @else

                                    <span class="bg-red-200 px-2 py-1 rounded">

                                        Rejected

                                    </span>

                                @endif

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="{{ asset('js/stock-opname.js') }}"></script>