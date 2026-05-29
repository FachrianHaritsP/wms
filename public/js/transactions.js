function resetCard(){

    document.getElementById('product_sku').innerText = '';
    document.getElementById('product_size').innerText = '';
    document.getElementById('product_color').innerText = '';
    document.getElementById('product_stock').innerText = '';
    document.getElementById('product_location').innerText = '';

}

function clearForm(){

    document.getElementById('product_id').value = '';
    document.getElementById('qty').value = '';

}

function fillProductCard(product){

    document.getElementById('product_sku').innerText = product.sku;
    document.getElementById('product_size').innerText = product.size;
    document.getElementById('product_color').innerText = product.color;
    document.getElementById('product_stock').innerText = product.stock;

    document.getElementById('product_location').innerText =
        product.rack_slot
        ? product.rack_slot.rack.rack_code + ' - ' + product.rack_slot.slot_code
        : '-';

}

function resetAfterTransaction(){

    clearForm();
    resetCard();

}

function handleScan(sku){

    clearForm();
    resetCard();

    fetch('/api/warehouse/scan/' + sku)

    .then(res => res.json())

    .then(response => {

        let product = response.data;

        if(product){

            document.getElementById('product_id').value = product.id;

            fillProductCard(product);

        } else {

            resetCard();

            alert('Produk tidak ditemukan');

        }

    })

}

function stockIn(){

    let product_id = document.getElementById('product_id').value;
    let qty = document.getElementById('qty').value;

    fetch('/api/warehouse/stock-in', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },

        body: JSON.stringify({
            product_id: product_id,
            qty: qty
        })

    })

    .then(res => res.json())

    .then(() => {

        loadHistory();

        resetAfterTransaction();

    });

}

function stockOut(){

    let product_id = document.getElementById('product_id').value;
    let qty = document.getElementById('qty').value;

    fetch('/warehouse/stock-out', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },

        body: JSON.stringify({
            product_id: product_id,
            qty: qty
        })

    })

    .then(res => res.json())

    .then(() => {

        loadHistory();

        resetAfterTransaction();

    });

}

function loadProducts(){

    fetch('/api/warehouse/products')
    .then(res => res.json())
    .then(response => {

        let data = response.data;

        let select = document.getElementById('product_id')

        data.forEach(item => {

            select.innerHTML += `
            <option value="${item.id}">
                ${item.name} - ${item.color} - ${item.size}
            </option>
            `
        })

    })

}

function loadHistory(){

    fetch('/api/warehouse/transactions')
    .then(res => res.json())
    .then(response => {

        let data = response.data;

        let table = document.getElementById('history_table')
        table.innerHTML = ''

        //bawaan item.created_at
        data.data.forEach(item => {

            table.innerHTML += `
            <tr>
                <td>${item.product.name}</td>
                <td>${item.type}</td>
                <td>${item.qty}</td>
                <td>${new Date(item.created_at).toLocaleString('id-ID')}</td> 
                <td>${item.user.name}</td>
            </tr>
            `
        })

    })

}

function startScanner(){

    const html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "environment" }, // kamera belakang
        {
            fps: 10,
            qrbox: 250
        },
        (decodedText) => {

            // hasil scan = SKU
            console.log("QR:", decodedText);

            handleScan(decodedText)

            html5QrCode.stop()

        }
    )
}

loadProducts()
loadHistory()