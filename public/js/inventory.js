let printProduct = null;
let printQueue = [];

function loadProducts(page = 1, search = ''){

    let table = document.getElementById('product_table');

    table.innerHTML ='<tr><td colspan="9" class="text-center">Loading...</td></tr>';

    fetch('/inventory/products?page=' + page + '&search=' + search)

    .then(res => res.json())
    .then(response => {

        let data = response.data.data;

        table.innerHTML = '';

        data.forEach(item => {

            let actionButtons = '';

            if (userRole === 'owner' || userRole === 'leader') {

                actionButtons += `
                    <button
                        class="btn btn-warning btn-sm"
                        onclick="openEditModal(${item.id})">
                        ✏ Edit
                    </button>

                    <button
                        class="btn btn-danger btn-sm"
                        onclick="openDeleteModal(${item.id})">
                        🗑 Delete
                    </button>
                `;

            }

            actionButtons += `
                <button
                    class="btn btn-info btn-sm"
                    onclick="openInfoModal(${item.id})">
                    ⓘ Info
                </button>
            `;


            let stockClass =
            item.stock <= 5 ? 'table-danger' : '';

            table.innerHTML += `
            <tr class="${stockClass}">

                <td class="d-none d-md-table-cell">${item.sku}</td>

                <td>${item.name}</td>

                <td class="d-none d-md-table-cell">${item.size}</td>

                <td class="d-none d-md-table-cell">${item.color}</td>

                <td>
                    ${item.stock <= 5
                    ? `<span class="badge bg-danger">${item.stock}</span>`
                    : item.stock}
                </td>

                <td class="d-none d-md-table-cell">
                    IDR. ${Number(item.price).toLocaleString('id-ID')}
                </td>

                <td class="d-none d-md-table-cell">
                    ${item.rack_slot
                    ? item.rack_slot.rack.rack_code + '-' + item.rack_slot.slot_code
                    : '-'}
                </td>

                <td class="text-nowrap">
                    <div class="d-grid gap-2 d-md-block">
                        ${actionButtons}
                    </div>
                </td>

            </tr>
            `;

        });

        if(data.length === 0){

            table.innerHTML = `

            <tr>

                <td
                    colspan="8"
                    class="text-center text-muted py-4">

                    Belum ada produk.

                </td>

            </tr>

            `;

        }
        renderProductPagination(response.data);

    });

}

function openAddModal(){

    document.getElementById('modalTitle').innerText = 'Tambah Produk'
    document.getElementById('sku').focus();

    document.getElementById('product_id').value = ''   
    document.getElementById('sku').value = ''
    document.getElementById('name').value = ''
    document.getElementById('size').value = ''
    document.getElementById('color').value = ''
    document.getElementById('stock').value = ''
    document.getElementById('price').value = ''
    document.getElementById('rack_slot_id').value = ''

    new bootstrap.Modal(document.getElementById('productModal')).show()
}//end

function saveProduct(){

    let id = document.getElementById('product_id').value;

    let sku = document.getElementById('sku').value.trim();
    let name = document.getElementById('name').value.trim();
    let size = document.getElementById('size').value;
    let color = document.getElementById('color').value;
    let stock = document.getElementById('stock').value;
    let price = document.getElementById('price').value;
    let rack_slot_id = document.getElementById('rack_slot_id').value;

    // VALIDASI
    if(sku === ''){
        alert('SKU wajib diisi');
        return;
    }

    if(name === ''){
        alert('Nama produk wajib diisi');
        return;
    }

    if(size === ''){
        alert('Ukuran produk wajib diisi');
        return;
    }

    if(color === ''){
        alert('Warna produk wajib diisi');
        return;
    }

    if(stock === ''){
        alert('Stock wajib diisi');
        return;
    }

    if(parseInt(stock) < 0){
        alert('Stock tidak boleh negatif');
        return;
    }

    let data = {
        sku: sku,
        name: name,
        size: size,
        color: color,
        stock: stock,
        price: price,
        rack_slot_id: rack_slot_id,
    };

    //api
    //let url = '/api/warehouse/products';
    //web
    let url = '/inventory/products';
    let method = 'POST';

    if(id){
        url += '/' + id;
        method = 'PUT';
    }

    fetch(url,{

        method: method,

        headers:{
            'Content-Type':'application/json',
            'Accept':'application/json',

            'X-CSRF-TOKEN':
            document.querySelector(
                'meta[name="csrf-token"]'
            ).content
        },

        body: JSON.stringify(data)

    })

    .then(res => res.json())

    .then(data => {

        if(!data.success){

            alert(data.message);
            return;

        }

        bootstrap.Modal
            .getInstance(
                document.getElementById(
                    'productModal'
                )
            )
            .hide();

        loadProducts();

        alert(data.message);

    })

    .catch(err => {

        console.log(err);

        alert(
            'Server tidak dapat dihubungi.'
        );

    });

}//end

function openEditModal(id){

    //api
    //fetch('/api/warehouse/products/' + id)
    //web
    fetch('/inventory/products/' + id)
    .then(res => res.json())
    .then(response => {
        let data = response.data

        document.getElementById('modalTitle').innerText = 'Edit Produk'

        document.getElementById('product_id').value = data.id
        document.getElementById('sku').value = data.sku
        document.getElementById('name').value = data.name
        document.getElementById('size').value = data.size
        document.getElementById('color').value = data.color
        document.getElementById('stock').value = data.stock
        document.getElementById('price').value = data.price
        document.getElementById('rack_slot_id').value = data.rack_slot_id,

        new bootstrap.Modal(document.getElementById('productModal')).show()

        console.log(data)

    })

}//end

function openInfoModal(id){

    //api
    //fetch('/api/warehouse/products/' + id)
    //web
    fetch('/inventory/products/' + id)

    .then(res => res.json())

    .then(response => {

        let data = response.data;
        printProduct = data;

        document.getElementById('info_sku')
            .innerText = data.sku;

        document.getElementById('info_name')
            .innerText = data.name;

        document.getElementById('info_size')
            .innerText = data.size;

        document.getElementById('info_color')
            .innerText = data.color;

        document.getElementById('info_stock')
            .innerText = data.stock;

        document.getElementById('info_price')
            .innerText =
            'IDR. ' + Number(data.price).toLocaleString('id-ID');

        document.getElementById('info_location')
            .innerText =
                data.rack_slot
                ? data.rack_slot.rack.rack_code
                    + '-' +
                    data.rack_slot.slot_code
                : '-';

        // QR
        let qrEl =
            document.getElementById('info_qr');

        qrEl.innerHTML = 'Loading QR...';


        fetch('/qr/' + encodeURIComponent(data.sku))

            .then(res => res.text())

            .then(svg => {

                qrEl.innerHTML = svg;

            })

            .catch(() => {

                qrEl.innerHTML =
                    '<span class="text-danger">QR gagal dimuat</span>';

            });



        

        new bootstrap.Modal(
            document.getElementById('infoModal')
        ).show();

    });

}//end

function openPrintModal(){

    if(!printProduct){
        return;
    }

    document.getElementById(
        'print_product_name'
    ).innerText = printProduct.name;

    document.getElementById(
    'print_product_price'
    ).innerText =
        'IDR. ' + Number(printProduct.price).toLocaleString('id-ID');

    document.getElementById(
        'print_qty'
    ).value = 1;

    const infoModal =
        bootstrap.Modal.getInstance(
            document.getElementById('infoModal')
        );

    if(infoModal){
        infoModal.hide();
    }

    const printModal =
        new bootstrap.Modal(
            document.getElementById('printModal')
        );

    printModal.show();

}//end print

function printQR(){

    if(!printProduct){
        return;
    }

    let qty = parseInt(
        document.getElementById('print_qty').value
    );

    if(!qty || qty < 1){
        alert('Jumlah QR minimal 1.');
        return;
    }

    // Buka window print sejak klik user
    // supaya tidak dianggap popup oleh browser
    let printWindow = window.open('', '_blank');

    if(!printWindow){
        alert('Popup diblokir oleh browser.');
        return;
    }

    // Ambil QR dari server
    fetch(
        '/qr/' +
        encodeURIComponent(printProduct.sku)
    )

    .then(res => res.text())

    .then(svg => {

        let pages = '';

        // 20 QR per A4
        for(let i = 0; i < qty; i += 30){

            let pageItems = '';

            let pageQty =
                Math.min(30, qty - i);

            for(let j = 0; j < pageQty; j++){

                pageItems += `
                    <div class="qr-item">

                        <div class="qr-name">
                            ${printProduct.name}
                        </div>

                        <div class="qr-detail">
                            ${printProduct.size} | ${printProduct.color}
                        </div>

                        <div class="qr-code">
                            ${svg}
                        </div>

                        <div class="qr-price">
                            IDR. ${Number(printProduct.price).toLocaleString('id-ID')}
                        </div>

                    </div>
                `;

            }

            pages += `
                <div class="a4-page">
                    ${pageItems}
                </div>
            `;

        }

        printWindow.document.write(`

            <!DOCTYPE html>

            <html>

            <head>

                <title>
                    Print QR - ${printProduct.name}
                </title>

                <style>

                    * {
                        box-sizing: border-box;
                    }

                    @page {
                        size: A4 portrait;
                        margin: 0;
                    }

                    html,
                    body {
                        margin: 0;
                        padding: 0;
                    }

                    body {
                        font-family: Arial, sans-serif;
                    }

                    .a4-page {

                        width: 210mm;
                        height: 297mm;

                        padding: 10mm;

                        display: grid;

                        grid-template-columns:
                            repeat(5, 1fr);

                        grid-template-rows:
                            repeat(6, 1fr);

                        gap: 2mm;

                        page-break-after: always;

                    }

                    .a4-page:last-child {
                        page-break-after: auto;
                    }

                    .qr-item {

                        display: flex;

                        flex-direction: column;

                        align-items: center;

                        justify-content: center;

                        text-align: center;

                        overflow: hidden;

                    }

                    .qr-name {

                        font-size: 10pt;

                        font-weight: 600;

                        margin-bottom: 2mm;

                        max-width: 42mm;

                        overflow: hidden;

                        white-space: nowrap;

                        text-overflow: ellipsis;

                    }

                    .qr-detail {
                        font-size: 8pt;
                        margin-bottom: 2mm;
                        white-space: nowrap;
                    }

                    .qr-code {

                        width: 20mm;

                        height: 20mm;

                        display: flex;

                        align-items: center;

                        justify-content: center;

                    }

                    .qr-code svg {

                        width: 20mm;

                        height: 20mm;

                        display: block;

                    }

                    .qr-price {
                        font-size: 8pt;
                        font-weight: 600;
                        margin-top: 2mm;
                        white-space: nowrap;
                    }
                    
                    

                </style>

            </head>

            <body>

                ${pages}

                <script>

                    window.onload = function(){

                        window.focus();

                        window.print();

                        setTimeout(function(){
                            window.close();
                        }, 500);

                    };

                <\/script>

            </body>

            </html>

        `);

        printWindow.document.close();

    })

    .catch(error => {

        printWindow.close();

        console.error(error);

        alert('Gagal membuat QR untuk dicetak.');

    });

}//end printqr

// queue print
function openPrintQueueModal(){

    renderPrintQueue();

    new bootstrap.Modal(
        document.getElementById('printQueueModal')
    ).show();

}//end

function addToPrintQueue(){

    if(!printProduct){
        return;
    }

    printQueue.push({
        product: printProduct,
        qty: 1
    });

    alert('Produk ditambahkan ke daftar print.');

}//end

//fungsi queue render
function renderPrintQueue(){

    const list = document.getElementById('printQueueList');

    if(printQueue.length === 0){
        list.innerHTML = `
            <div class="text-center text-muted py-3">
                Belum ada produk.
            </div>
        `;
        document.getElementById('printQueueTotal')
        .innerText = 0;

        return;
    }

    let total = printQueue.reduce(
    (sum, item) => sum + item.qty,
    0
    );

    document.getElementById('printQueueTotal')
        .innerText = total;

    list.innerHTML = printQueue.map((item, index) => `
        <div class="d-flex justify-content-between align-items-center border-bottom py-2">

            <div>
                <strong>${item.product.name}</strong>
                <br>
                <small>
                    ${item.product.size} | ${item.product.color}
                </small>
            </div>

            <div class="d-flex align-items-center gap-2">

                <button
                    class="btn btn-outline-secondary btn-sm"
                    onclick="changePrintQty(${index}, -1)">
                    −
                </button>

               <input
                    type="number"
                    class="form-control form-control-sm"
                    style="width: 80px;"
                    min="1"
                    value="${item.qty}"
                    onchange="changePrintQtyInput(${index}, this.value)">

                <button
                    class="btn btn-outline-secondary btn-sm"
                    onclick="changePrintQty(${index}, 1)">
                    +
                </button>

                <button
                    class="btn btn-outline-danger btn-sm"
                    onclick="removePrintQueue(${index})">
                    🗑
                </button>

            </div>

        </div>
    `).join('');
}//end

//fungsi queue print
function changePrintQty(index, change){

    printQueue[index].qty += change;

    if(printQueue[index].qty < 1){
        printQueue[index].qty = 1;
    }

    renderPrintQueue();
}//end

//fungsi queue print
function removePrintQueue(index){

    printQueue.splice(index, 1);

    renderPrintQueue();

}//end

function changePrintQtyInput(index, value){

    let qty = parseInt(value);

    if(!qty || qty < 1){
        qty = 1;
    }

    printQueue[index].qty = qty;

    renderPrintQueue();

}//end

function printAllQueue(){

    if(printQueue.length === 0){
        alert('Belum ada produk dalam daftar print.');
        return;
    }

    let printWindow = window.open('', '_blank');

    if(!printWindow){
        alert('Popup diblokir oleh browser.');
        return;
    }

    Promise.all(
        printQueue.map(item =>
            fetch(
                '/qr/' +
                encodeURIComponent(item.product.sku)
            )
            .then(res => res.text())
            .then(svg => ({
                product: item.product,
                qty: item.qty,
                svg: svg
            }))
        )
    )
    .then(results => {

        //console.log(results);

        let pages = '';
        let items = [];

        // Buat semua QR berdasarkan qty
        results.forEach(item => {

            for(let j = 0; j < item.qty; j++){

                items.push(`
                    <div class="qr-item">

                        <div class="qr-name">
                            ${item.product.name}
                        </div>

                        <div class="qr-detail">
                            ${item.product.size} | ${item.product.color}
                        </div>

                        <div class="qr-code">
                            ${item.svg}
                        </div>

                        <div class="qr-price">
                            IDR. ${Number(item.product.price).toLocaleString('id-ID')}
                        </div>

                    </div>
                `);

            }

        });

        // Pecah menjadi maksimal 30 QR per A4
        for(let i = 0; i < items.length; i += 30){

            let pageItems =
                items.slice(i, i + 30).join('');

            pages += `
                <div class="a4-page">
                    ${pageItems}
                </div>
            `;

        }

        // Tulis ke window print SATU KALI
        printWindow.document.write(`

            <!DOCTYPE html>

            <html>

            <head>

                <title>Print QR</title>

                <style>

                    * {
                        box-sizing: border-box;
                    }

                    @page {
                        size: A4 portrait;
                        margin: 0;
                    }

                    html,
                    body {
                        margin: 0;
                        padding: 0;
                    }

                    body {
                        font-family: Arial, sans-serif;
                    }

                    .a4-page {

                        width: 210mm;
                        height: 297mm;

                        padding: 10mm;

                        display: grid;

                        grid-template-columns:
                            repeat(5, 1fr);

                        grid-template-rows:
                            repeat(6, 1fr);

                        gap: 2mm;

                        page-break-after: always;

                    }

                    .a4-page:last-child {
                        page-break-after: auto;
                    }

                    .qr-item {

                        display: flex;

                        flex-direction: column;

                        align-items: center;

                        justify-content: center;

                        text-align: center;

                        overflow: hidden;

                    }

                    .qr-name {

                        font-size: 10pt;

                        font-weight: 600;

                        margin-bottom: 2mm;

                        max-width: 42mm;

                        overflow: hidden;

                        white-space: nowrap;

                        text-overflow: ellipsis;

                    }

                    .qr-detail {

                        font-size: 8pt;

                        margin-bottom: 2mm;

                        white-space: nowrap;

                    }

                    .qr-code {

                        width: 20mm;

                        height: 20mm;

                        display: flex;

                        align-items: center;

                        justify-content: center;

                    }

                    .qr-code svg {

                        width: 20mm;

                        height: 20mm;

                        display: block;

                    }

                    .qr-price {

                        font-size: 8pt;

                        font-weight: 600;

                        margin-top: 2mm;

                    }

                </style>

            </head>

            <body>

                ${pages}

                <script>

                    window.onload = function(){

                       
                        window.focus();
                        window.print();

                        setTimeout(function(){
                            window.close();
                        }, 500);

                    };

                <\/script>

            </body>

            </html>

        `);

        printWindow.document.close();
                printQueue = [];
        renderPrintQueue();

        const queueModal =
            bootstrap.Modal.getInstance(
                document.getElementById('printQueueModal')
            );

        if(queueModal){
            queueModal.hide();
        }

    })
    .catch(error => {

        printWindow.close();

        console.error(error);

        alert('Gagal mengambil QR.');

    });

}//end

function openDeleteModal(id){

    document.getElementById('delete_id').value = id

    new bootstrap.Modal(document.getElementById('deleteModal')).show()
}//end

function confirmDelete(){

    let id =
        document.getElementById(
            'delete_id'
        ).value;

    let btn =
        document.getElementById(
            'btnDelete'
        );

    btn.disabled = true;
    btn.innerText = 'Deleting...';

    //'/inventory/products/' + id, | api '/api/warehouse/products/' + id,
    fetch('/inventory/products/' + id,{

        method:'DELETE',

        headers:{

            'Accept':'application/json',

            'X-CSRF-TOKEN':
            document.querySelector(
                'meta[name="csrf-token"]'
            ).content

        }

    })

    .then(res => res.json())

    .then(data => {

        if(!data.success){

            alert(data.message);
            return;

        }

        bootstrap.Modal
            .getInstance(
                document.getElementById(
                    'deleteModal'
                )
            )
            .hide();

        loadProducts();

        alert(data.message);

    })

    .catch(err => {

        console.log(err);

        alert(
            'Server tidak dapat dihubungi.'
        );

    })

    .finally(() => {

        btn.disabled = false;
        btn.innerText = 'Delete';

    });

}


document.addEventListener('DOMContentLoaded', function(){

    loadProducts()

    document.getElementById('search').addEventListener('keyup', function() {
        loadProducts(1,this.value)
        console.log("search:", this.value)
    })

})

function renderProductPagination(meta){

    let container =
        document.getElementById('productPagination');

    container.innerHTML = '';

    if(meta.last_page <= 1){
        return;
    }

    let html = `
        <div class="btn-group">
    `;

    html += `
        <button
            class="btn btn-outline-primary btn-sm"
            ${meta.current_page === 1 ? 'disabled' : ''}
            onclick="loadProducts(
                ${meta.current_page - 1},
                '${document.getElementById('search').value}'
            )">
            Previous
        </button>
    `;

    for(let page = 1; page <= meta.last_page; page++){

        html += `
            <button
                class="btn ${
                    page === meta.current_page
                        ? 'btn-primary'
                        : 'btn-outline-primary'
                } btn-sm"
                onclick="loadProducts(
                    ${page},
                    '${document.getElementById('search').value}'
                )">
                ${page}
            </button>
        `;
    }

    html += `
        <button
            class="btn btn-outline-primary btn-sm"
            ${meta.current_page === meta.last_page ? 'disabled' : ''}
            onclick="loadProducts(
                ${meta.current_page + 1},
                '${document.getElementById('search').value}'
            )">
            Next
        </button>
    `;

    html += `</div>`;

    container.innerHTML = html;
}

loadProducts();