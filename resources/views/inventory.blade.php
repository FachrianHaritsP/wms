@extends('layouts.main')

@section('content')
<div class="table-responsive">
    <h2 class="mt-2">Inventori</h2>

    <!--Search + Add -->
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
            <input type="text" id="search" class="form-control" placeholder="Cari SKU / Nama">
        </div>
    @if(in_array(auth()->user()->role, ['owner', 'leader']))
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
            <button class="btn btn-primary w-100" onclick="openAddModal()">Tambah Produk</button>
        </div>
    @endif
        {{-- tabel --}}
        <table class="table table-bordered table-striped table-sm small">
            <thead class="table-dark">
                
                <tr>
                    <th class="d-none d-md-table-cell">SKU</th>
                    <th>Nama</th>
                    <th class="d-none d-md-table-cell">Ukuran</th>
                    <th class="d-none d-md-table-cell">Warna</th>
                    <th>Stock</th>
                    <th class="d-none d-md-table-cell">Lokasi</th>
                    <th>Aksi</th>
                </tr>
                           
            </thead>

            <tbody id="product_table"></tbody>

        </table>
        <div id="productPagination"
            class="d-flex justify-content-center mt-3">
        </div>

        <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="product_id">

                <div class="mb-2">
                    SKU
                    <input type="text" id="sku" class="form-control" placeholder="SKU">
                </div>

                <div class="mb-2">
                    Nama
                    <input type="text" id="name" class="form-control" placeholder="Name">
                </div>

                <div class="mb-2">
                    Ukuran
                    <input type="text" id="size" class="form-control" placeholder="Size">
                </div>

                <div class="mb-2">
                    Warna
                    <input type="text" id="color" class="form-control" placeholder="Color">
                </div>

                <div class="mb-2">
                    Stock
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
                    <h5 class="modal-title">Hapus produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>Yakin ingin menghapus produk ini?</p>
                    <input type="hidden" id="delete_id">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="btnDelete" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
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
                        Detail Produk
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">

                    </button>

                </div>

                <div class="modal-body">

                    <p>
                        <b>SKU:</b>
                        <span id="info_sku"></span>
                    </p>

                    <p>
                        <b>Nama:</b>
                        <span id="info_name"></span>
                    </p>

                    <p>
                        <b>Ukuran:</b>
                        <span id="info_size"></span>
                    </p>

                    <p>
                        <b>Warna:</b>
                        <span id="info_color"></span>
                    </p>

                    <p>
                        <b>Stock:</b>
                        <span id="info_stock"></span>
                    </p>

                    <p>
                        <b>Lokasi:</b>
                        <span id="info_location"></span>
                    </p>

                  

                    <hr>

                    <div class="text-center">

                        <h6>QR Produk</h6>

                        <div id="info_qr" class="d-flex justify-content-center">
                        </div>
                      
                        <button type="button" class="btn btn-info btn-sm mt-6" onclick="openPrintModal()">
                            Print QR
                        </button>
                   
                    </div> 

                </div>

            </div>

        </div>

    </div>{{-- end info modal --}}

    {{-- Print QR Modal --}}
    <div class="modal fade"
        id="printModal"
        tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Print QR
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <p>
                        <b>Produk:</b>
                        <span id="print_product_name"></span>
                    </p>

                    <div class="mb-3">

                        <label for="print_qty"
                            class="form-label">
                            Jumlah QR
                        </label>

                        <input
                            type="number"
                            id="print_qty"
                            class="form-control"
                            min="1"
                            value="1">

                    </div>

                    <small class="text-muted">
                        Maksimal 30 QR per lembar A4.
                    </small>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="printQR()">
                        🖨 Print
                    </button>

                </div>

            </div>

        </div>

    </div> {{-- end print modal --}}

</div>
 
<script>
    const userRole = @json(auth()->user()->role);
</script>
<script src="/js/inventory.js"></script>

@endsection