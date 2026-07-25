function loadProducts(page = 1, search = ''){

    let table = document.getElementById('product_table');

    table.innerHTML ='<tr><td colspan="8" class="text-center">Loading...</td></tr>';

    //api
    //fetch('/api/warehouse/products?page='+ page +'&search=' + search)
    //web
    fetch('/inventory/products?page=' + page + '&search=' + search)

    .then(res => res.json())
    .then(response => {

        let data = response.data.data;

        table.innerHTML = '';

        data.forEach(item => {

            let stockClass =
            item.stock <= 5 ? 'table-danger' : '';

            table.innerHTML += `
            <tr class="${stockClass}">

                <td>${item.sku}</td>

                <td>${item.name}</td>

                <td class="d-none d-md-table-cell">${item.size}</td>

                <td class="d-none d-md-table-cell">${item.color}</td>

                <td>
                    ${item.stock <= 5
                    ? `<span class="badge bg-danger">${item.stock}</span>`
                    : item.stock}
                </td>

                <td class="d-none d-md-table-cell">
                    ${item.rack_slot
                    ? item.rack_slot.rack.rack_code + '-' + item.rack_slot.slot_code
                    : '-'}
                </td>

                <td>
                    <button class="btn btn-warning btn-sm mt-1"
                    onclick="openEditModal(${item.id})">
                    ✏ Edit
                    </button>

                    <button class="btn btn-danger btn-sm mt-1"
                    onclick="openDeleteModal(${item.id})">
                    🗑 Delete
                    </button>
                    
                    <button class="btn btn-info btn-sm mt-1"
                    onclick="openInfoModal(${item.id})">

                    ⓘ Info

                    </button>
                </td>

                <td id="qr-${item.id}" class="d-none d-md-table-cell"></td>

            </tr>
            `;

        });

        data.forEach(item => {

            fetch('/qr/' + encodeURIComponent(item.sku))

            .then(res => res.text())

            .then(svg => {

                let qrEl =
                document.getElementById('qr-' + item.id);

                if(qrEl){
                    qrEl.innerHTML = svg;
                }

            });

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

            return;

        }

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

        document.getElementById('info_location')
            .innerText =
                data.rack_slot
                ? data.rack_slot.rack.rack_code
                    + '-' +
                    data.rack_slot.slot_code
                : '-';

        new bootstrap.Modal(
            document.getElementById('infoModal')
        ).show();

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