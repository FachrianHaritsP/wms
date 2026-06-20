@extends('layouts.main')

@section('content')
<div class="table-responsive">
    <h2 class="mt-2">Inventory</h2>

    <!--Search + Add -->
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
            <input type="text" id="search" class="form-control" placeholder="Search SKU / Name">
            <button class="btn btn-primary w-100" onclick="openAddModal()">Add Product</button>
        </div>

        {{-- tabel --}}
        <table class="table table-bordered table-striped table-sm small">
            <thead class="table-dark">
                
                <tr>
                    <th>SKU</th>
                    <th>Name</th>
                    <th class="d-none d-md-table-cell">Size</th>
                    <th class="d-none d-md-table-cell">Color</th>
                    <th>Stock</th>
                    <th class="d-none d-md-table-cell">Location</th>
                    <th>Action</th>
                    <th class="d-none d-md-table-cell">QR</th>
                </tr>
                           
            </thead>

            <tbody id="product_table"></tbody>

        </table>

        <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="product_id">

                <div class="mb-2">
                    <input type="text" id="sku" class="form-control" placeholder="SKU">
                </div>

                <div class="mb-2">
                    <input type="text" id="name" class="form-control" placeholder="Name">
                </div>

                <div class="mb-2">
                    <input type="text" id="size" class="form-control" placeholder="Size">
                </div>

                <div class="mb-2">
                    <input type="text" id="color" class="form-control" placeholder="Color">
                </div>

                <div class="mb-2">
                    <input type="number" id="stock" class="form-control" placeholder="Stock">
                </div>

                <div class="mb-3">
                    <label>Rack Slot</label>

                    <select id="rack_slot_id" class="form-control">

                        <option value="">-- Pilih Slot --</option>

                        @foreach($rackSlots as $slot)

                            <option value="{{ $slot->id }}">
                                {{ $slot->rack->rack_code }}-{{ $slot->slot_code }}
                            </option>

                        @endforeach

                    </select>
                </div>

            </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" onclick="saveProduct()">Save</button>
                </div>

            </div>
        </div>
    </div>{{-- end modal --}}

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>Yakin ingin menghapus produk ini?</p>
                    <input type="hidden" id="delete_id">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                </div>

            </div>
        </div>
    </div>{{-- end delete modal --}}

    <!-- Info Modal -->
    <div class="modal fade"
        id="infoModal"
        tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Product Detail
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">

                    </button>

                </div>

                <div class="modal-body">

                    <p><b>SKU:</b>
                        <span id="info_sku"></span>
                    </p>

                    <p><b>Name:</b>
                        <span id="info_name"></span>
                    </p>

                    <p><b>Size:</b>
                        <span id="info_size"></span>
                    </p>

                    <p><b>Color:</b>
                        <span id="info_color"></span>
                    </p>

                    <p><b>Stock:</b>
                        <span id="info_stock"></span>
                    </p>

                    <p><b>Location:</b>
                        <span id="info_location"></span>
                    </p>

                </div>

            </div>

        </div>

    </div>{{-- end info modal --}}

</div>
 

<script src="/js/inventory.js"></script>

@endsection