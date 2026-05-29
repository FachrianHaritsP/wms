/* function loadProducts(search = ''){

    let table = document.getElementById('product_table')
    table.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>'

    fetch('/api/warehouse/products?search=' + search) //  http://127.0.0.1:8000/api/warehouse/products
    .then(res =>res.json())
    .then(response => {
        let data =response.data;
        console.log(data)

        table.innerHTML = ''

        data.forEach(item => {
        let stockClass = item.stock <= 5 ? 'table-danger' : ''  

            table.innerHTML +=`
            <tr class="${stockClass}">
                <td>${item.sku}</td>
                <td>${item.name}</td>
                <td>${item.size}</td>
                <td>${item.color}</td>
                <td>
                     ${item.stock <= 5 
                    ? `<span class="badge bg-danger">${item.stock}</span>` 
                     : item.stock
                     }
                </td>
                <td>
                    ${item.rack_slot 
                        ? item.rack_slot.rack.rack_code + '-' + item.rack_slot.slot_code
                        : '-'}
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="openEditModal(${item.id})"> ✏ Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="openDeleteModal(${item.id})"> 🗑 Delete</button>
                </td>
                <td id="qr-${item.id}"></td>
            </tr>
            `
        
        fetch('/qr/' + encodeURIComponent(item.sku))
        .then(res => res.text())
        .then(svg => {

            let qrEl = document.getElementById('qr-' + item.id);
            console.log(item)
            console.log(item.id) 
            if(qrEl){
                qrEl.innerHTML = svg;
            } 
            //document.getElementById('qr-' + item.id).innerHTML = svg
        })
            
        })//end data

         if(data.length === 0){
            table.innerHTML = '<tr><td colspan="6">No data found</td></tr>'
        }


    })//end fetch
}//end func */

function loadProducts(search = ''){

    let table = document.getElementById('product_table');

    table.innerHTML =
    '<tr><td colspan="8" class="text-center">Loading...</td></tr>';

    fetch('/api/warehouse/products?search=' + search)

    .then(res => res.json())

    .then(response => {

        let data = response.data;

        table.innerHTML = '';

        data.forEach(item => {

            let stockClass =
            item.stock <= 5 ? 'table-danger' : '';

            table.innerHTML += `
            <tr class="${stockClass}">

                <td>${item.sku}</td>

                <td>${item.name}</td>

                <td>${item.size}</td>

                <td>${item.color}</td>

                <td>
                    ${item.stock <= 5
                    ? `<span class="badge bg-danger">${item.stock}</span>`
                    : item.stock}
                </td>

                <td>
                    ${item.rack_slot
                    ? item.rack_slot.rack.rack_code + '-' + item.rack_slot.slot_code
                    : '-'}
                </td>

                <td>
                    <button class="btn btn-warning btn-sm"
                    onclick="openEditModal(${item.id})">
                    ✏ Edit
                    </button>

                    <button class="btn btn-danger btn-sm"
                    onclick="openDeleteModal(${item.id})">
                    🗑 Delete
                    </button>
                </td>

                <td id="qr-${item.id}"></td>

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
            table.innerHTML = '<tr><td colspan="6">No data found</td></tr>';
        } 

    });

    
}

function openAddModal(){

    document.getElementById('modalTitle').innerText = 'Add Product'

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

    let id = document.getElementById('product_id').value

    let data = {
        sku: document.getElementById('sku').value,
        name: document.getElementById('name').value,
        size: document.getElementById('size').value,
        color: document.getElementById('color').value,
        stock: document.getElementById('stock').value,
        rack_slot_id: document.getElementById('rack_slot_id').value,
    }

    let url = '/api/warehouse/products'
    let method = 'POST'

    if(id){
        url += '/' + id
        method = 'PUT'
    }

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => {

        bootstrap.Modal.getInstance(document.getElementById('productModal')).hide()
        loadProducts()

    })
}//end

function openEditModal(id){

    fetch('/api/warehouse/products/' + id)
    .then(res => res.json())
    .then(data => {

        document.getElementById('modalTitle').innerText = 'Edit Product'

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

function openDeleteModal(id){

    document.getElementById('delete_id').value = id

    new bootstrap.Modal(document.getElementById('deleteModal')).show()
}//end


document.addEventListener('DOMContentLoaded', function(){

    loadProducts()

    document.getElementById('search').addEventListener('keyup', function() {
        loadProducts(this.value)
        console.log("search:", this.value)
    })

})
