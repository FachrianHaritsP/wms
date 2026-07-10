
let transactions = [];

const role = document.querySelector('meta[name="user-role"]').content;
const transactionType = document.querySelector('meta[name="transaction-type"]')?.content;

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

    let product_id =
        document.getElementById(
            'product_id'
        ).value;

    let qty =
        document.getElementById(
            'qty'
        ).value;

    let btn =
        document.getElementById(
            'btnStockIn'
        );

    btn.disabled = true;
    btn.innerText = 'Saving...';

    if(product_id === ''){

        alert('Produk wajib dipilih');

        btn.disabled = false;
        btn.innerText = 'Save Stock-In';

        return;

    }

    if(qty === ''){

        alert('Qty wajib diisi');

        btn.disabled = false;
        btn.innerText = 'Save Stock-In';

        return;

    }

    if(parseInt(qty) <= 0){

        alert('Qty harus lebih dari 0');

        btn.disabled = false;
        btn.innerText = 'Save Stock-In';

        return;

    }

    fetch('/warehouse/stock-in',{

        method:'POST',

        headers:{

            'Content-Type':'application/json',

            'X-CSRF-TOKEN':
            document.querySelector(
                'meta[name="csrf-token"]'
            ).content

        },

        body: JSON.stringify({

            product_id: product_id,
            qty: qty

        })

    })

    .then(res => res.json())

    .then(data => {

        if(!data.success){

            alert(data.message);

            return;

        }

        alert(data.message);

        loadHistory();

        resetAfterTransaction();

    })

    .catch(err => {

        console.log(err);

        alert(
            'Server tidak dapat dihubungi.'
        );

    })

    .finally(() => {

        btn.disabled = false;
        btn.innerText = 'Save Stock-In';

    });

}

function stockOut(){

    let product_id = document.getElementById('product_id').value;
    let qty = document.getElementById('qty').value;
    let btn =
        document.getElementById(
            'btnStockOut'
        );

    btn.disabled = true;
    btn.innerText = 'Saving...';

    if(product_id === ''){
    alert('Produk wajib dipilih');

    btn.disabled = false;
    btn.innerText = 'Save Stock-Out';

    return;
    }

    if(qty === ''){
        alert('Qty wajib diisi');

        btn.disabled = false;
        btn.innerText = 'Save Stock-In';

        return;
    }

    if(parseInt(qty) <= 0){
        alert('Qty harus lebih dari 0');

        btn.disabled = false;
        btn.innerText = 'Save Stock-In';

        return;
    }

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

    .then(data => {

    if(!data.success){

        alert(data.message);

        return;

    }

        alert(data.message);
        loadHistory();
        resetAfterTransaction();

    })
    .catch(err => {

    console.log(err);

    alert(
        'Server tidak dapat dihubungi.'
    );

    })
    .finally(()=>{

        btn.disabled = false;
        btn.innerText = 'Save Stock-Out';

    });

}

function loadProducts(){

    fetch('/api/warehouse/products')
    .then(res => res.json())
    .then(response => {

        let data = response.data.data;

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

    let startDate =
        document.getElementById(
            'start_date'
        ).value;
    let endDate =
        document.getElementById(
            'end_date'
        ).value;
    
    if(
        startDate &&
        endDate &&
        startDate > endDate
    ){

        alert(
            'Start date cannot be greater than end date'
        );

        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';

        return;

    }

    fetch(
    '/api/warehouse/transactions?type='
    + transactionType
    + '&start_date='
    + startDate
    + '&end_date='
    + endDate
    )
    .then(res => res.json())
    .then(response => {

        let data = response.data;

        transactions = data.data;
       
        let table = document.getElementById('history_table')
        table.innerHTML = ''

        //bawaan item.created_at
        data.data.forEach(item => {
            console.log(item);
            let action = '';
            //date vali
            let today =
            new Date()
            .toISOString()
            .split('T')[0];

            let transactionDate =
            item.created_at
            .split('T')[0];

            console.log('today:', today);
            console.log('transactionDate:', transactionDate);
            console.log('created_at:', item.created_at);

            if(role === 'leader'){

                action = `
                   <button
                    class="btn btn-warning btn-sm"
                    onclick="editTransaction(${item.id})">

                    Edit

                </button>
                `;

            }else if(
                    role === 'staff'
                    &&
                    transactionDate === today
                ){

                    action = `
                    <button
                            class="btn btn-warning btn-sm"
                            onclick="editTransaction(${item.id})">

                            Edit

                        </button>
                    `;

                }
            table.innerHTML += `
            <tr>
                <td>${item.product.name}</td>
                <td>${item.qty}</td>
                <td>${item.user.name}</td>
                <td>${action}</td>
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
        ).catch(err=>{

            console.log(err);

            alert(err+' '+ console.log(navigator.mediaDevices)+' '+console.log(navigator.mediaDevices?.getUserMedia));

    });
    
}

function fillProductCardEdit(product){

    document.getElementById('product_sku').innerText = product.sku;
    document.getElementById('product_size').innerText = product.size;
    document.getElementById('product_color').innerText = product.color;
    document.getElementById('product_stock').innerText = product.stock;

    document.getElementById('product_location').innerText =
        product.rack_slot
        ? product.rack_slot.rack.rack_code + ' - ' + product.rack_slot.slot_code
        : '-';

}

function editTransaction(id){

    let transaction =
        transactions.find(
            item => item.id == id
        );

    document
    .getElementById('transaction_id')
    .value =
    transaction.id;

    document
    .getElementById('product_id')
    .value =
    transaction.product_id;
    
    document
    .getElementById('qty')
    .value =
    transaction.qty;

    if(transactionType === 'in'){

        document.getElementById(
            'btnStockIn'
        ).innerText =
        'Update Transaction';

    }else{

        document.getElementById(
            'btnStockOut'
        ).innerText =
        'Update Transaction';

    }

    document.getElementById('qty').focus();

    fillProductCardEdit(transaction.product);
}



function updateTransaction(){

    let transaction_id =
        document.getElementById(
            'transaction_id'
        ).value;

    let qty =
        document.getElementById(
            'qty'
        ).value;

    if(!transaction_id){

    alert('Pilih transaksi terlebih dahulu');

    return;
    }

    fetch(
        '/transactions/' + transaction_id,
        {

            method: 'PUT',

            headers: {

                'Content-Type':'application/json',

                'X-CSRF-TOKEN':
                document.querySelector(
                    'meta[name="csrf-token"]'
                ).content

            },

            body: JSON.stringify({

                qty: qty

            })

        }

    )
    .then(res => res.json())
    .then(data => {

        alert(data.message);

        loadHistory();

        resetAfterTransaction();
        document.getElementById(
            'transaction_id'
        ).value = '';

        if(transactionType === 'in'){

            document.getElementById(
                'btnStockIn'
            ).innerText =
            'Save Stock-In';

        }else{

            document.getElementById(
                'btnStockOut'
            ).innerText =
            'Save Stock-Out';

        }if(transactionType === 'in'){

            document.getElementById(
                'btnStockIn'
            ).innerText =
            'Save Stock-In';

        }else{

            document.getElementById(
                'btnStockOut'
            ).innerText =
            'Save Stock-Out';

        }

    });

}

function submitTransaction(){

        let transactionId =
        document.getElementById(
            'transaction_id'
        ).value;


    if(transactionId){

        updateTransaction();

        return;

    }

    if(transactionType === 'in'){

        stockIn();

    }else{

        stockOut();
    }

}

loadProducts()
loadHistory()