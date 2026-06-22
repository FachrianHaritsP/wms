@extends('layouts.main')

@section('content')

<div class="contaier-fluid">

    <h3 class="mb-4 font-bold text-xl">
        Stock Opname
    </h3>

    <input
    type="hidden"
    id="total_products"
    value="{{ \App\Models\Product::count() }}"
    >

    <div class="row">

        {{-- Scan Card --}}
        <div class="col-lg-4 mb-3">
            <div class="mb-4">        
                    <span id="counter" class="bg-blue-200 px-3 py-2 rounded">Items Checked : 0</span>
            </div >
                Session :
                <span id="session_code">
                    -
                </span>
                <div>
                <button id="startBtn" onclick="startOpname()" class="btn btn-primary md-3">
                    Start Opname
                </button>
                <button id="closeBtn" onclick="closeSession()"
                        class="btn btn-danger">
                    Close Session
                </button>
            </div>   
            <div id="opnameForm" style="display:none;">
                <div class="card p-2">
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
        <div class="col-lg-8">

            {{-- History Opname --}}
            <div class="card p-2">
                <div class="table-responsive">

                    <h5 class="font-bold mb-3">
                        History Stock Opname
                    </h5>

                    <table class="table table-bordered table-striped table-sm small">

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

                                <th class="border px-2 py-2 d-none d-md-table-cell">
                                    Difference
                                </th>

                                <th class="border px-2 py-2">
                                    Status
                                </th>

                                <th class="border px-2 py-2 d-none d-md-table-cell">
                                    User
                                </th>

                            </tr>

                        </thead>

                        <tbody id="history_table">

                        </tbody>

                    </table>

                    <div class="mt-4">

                        {{ $opnames->links() }}

                    </div>
                </div>
            </div>

            {{-- Related Returns --}}
            <div class="accordion mt-4" id="returnAccordion">    

                <h2>

                    <button class="btn btn-secondary w-100" onclick="toggleReturns()">

                        Related Returns ▼

                    </button>

                </h2>
       
                <div id="returnSection" style="display:none;" class="mt-3">   
                    {{-- bungkus --}}

                    <table class="table table-bordered table-striped table-sm small">

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

            </div> <! --sss -->

        </div>

    </div>

</div>


<script>

function toggleReturns(){

    let section =
        document.getElementById('returnSection');

    if(section.style.display === 'none'){

        section.style.display = 'block';

    }else{

        section.style.display = 'none';

    }

}

</script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="{{ asset('js/stock-opname.js') }}"></script>

@endsection