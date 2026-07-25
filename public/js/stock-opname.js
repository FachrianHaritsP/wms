let sessionCounter = 0;

let opnameSession = '';

if(opnameSession){

    document.getElementById(
        'opnameForm'
    ).style.display = 'block';

    updateSessionLabel('OPEN');
    refreshOpnameHistory();

}else{

    document.getElementById(
        'history_table'
    ).innerHTML = `
            <tr>
                <td colspan="6"
                    class="text-center">

                    Stock Opname belum dimulai

                </td>
            </tr>
    `;

}

function checkActiveSession(){


    fetch(
        '/warehouse/stock-opname/active'
    )

    .then(res => res.json())

    .then(data => {

        if (data.success) {

        opnameSession = data.data.session_code;

        document.getElementById(
            'opnameForm'
        ).style.display = 'block';

        updateSessionLabel('OPEN');

        document.getElementById(
            'startBtn'
        ).disabled = true;

        document.getElementById(
            'closeBtn'
        ).disabled = false;

        refreshOpnameHistory();

    } else {

        opnameSession = null;

        document.getElementById(
            'opnameForm'
        ).style.display = 'none';

        document.getElementById(
            'startBtn'
        ).disabled = false;

        document.getElementById(
            'closeBtn'
        ).disabled = true;

        document.getElementById(
            'session_code'
        ).innerText = '-';

        let totalProducts =
            document.getElementById(
                'total_products'
            ).value;

        document.getElementById(
            'counter'
        ).innerText =
            'Items Checked : 0/' + totalProducts;

        document.getElementById(
            'history_table'
        ).innerHTML = `
            <tr>
                <td colspan="6" class="text-center">
                    Stock Opname belum dimulai
                </td>
            </tr>
        `;
    }
    });


}

function refreshFill(){
    document.getElementById('physical_stock').value = '';
    document.getElementById('product_id_drop').value= '';
    document.getElementById('product_sku').innerText = '';
    document.getElementById('product_name').innerText = '';
    document.getElementById('product_stock').innerText = '';
    document.getElementById('product_location').innerText = ''; 
}

function startOpname(){

    fetch('/warehouse/stock-opname/start', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        }

    })

    .then(res => res.json())

    .then(data => {

        if(data.success){

            opnameSession = data.data.session_code;

            document.getElementById('opnameForm').style.display = 'block';

            updateSessionLabel('OPEN');

            document.getElementById('startBtn').disabled = true;
            document.getElementById('closeBtn').disabled = false;

            refreshOpnameHistory();

            alert(data.message);

        }else{

            alert(data.message);

        }

    });

}


// scan product
function scanProduct(sku){


    fetch('warehouse/scan/' + sku) //scanner

    .then(res => res.json())

    .then(data => {

        console.log(data);

        // ambil product
        let product = data.data;

        // set hidden id
        document.getElementById('product_id').value = product.id;

        // tampil info
        document.getElementById('product_sku').innerText = product.sku;
        document.getElementById('product_name').innerText = product.name;
        document.getElementById('product_stock').innerText = product.stock;
        document.getElementById('product_location').innerText =  document.getElementById('product_location').innerText = product.rack_slot.rack.rack_code + ' - ' + product.rack_slot.slot_code;//product.rack + ' - ' + product.slot; 

    })

    .catch(err => {

        console.log(err);

        alert('Product tidak ditemukan');

    });

}

function saveOpname(){

    let product_id = document.getElementById('product_id').value;
    let physical_stock = document.getElementById('physical_stock').value;


    let btn =
        document.getElementById(
            'btnSaveOpname'
        );

    btn.disabled = true;
    btn.innerText = 'Saving...';

    fetch('/warehouse/stock-opname', {

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
            physical_stock: physical_stock,
            session_code: opnameSession

        })

    })

    .then(res => res.json())
    .then(data => {

        console.log(data);

        alert('Stock opname berhasil');
        refreshOpnameHistory();
      
        refreshFill();

    })

    .catch(err => {

        console.log(err);

        alert('Terjadi error : ' + err);

    })
    .finally(()=>{

        btn.disabled = false;
        btn.innerText = 'Save Opname';

    });

}

   // 
   //${item.user.name} 
   //${item.match_status}
function refreshOpnameHistory(){

    console.log('Session ' + opnameSession);

    fetch(
    '/warehouse/stock-opname/history?session_code='
    + opnameSession)

    .then(res => res.json())

    .then(result => {

        console.log(result);

        let table =
            document.getElementById(
                'history_table'
            );

        table.innerHTML = '';

        result.data.forEach(item => {

            let totalProducts =
                document.getElementById(
                    'total_products'
                ).value;

            document.getElementById(
                'counter'
            ).innerText =
            'Items Checked : '
            + result.data.length
            + '/'
            + totalProducts;

             let statusBadge = '';

            if( item.match_status  === 'match'){ //item.match_status | item.status

                statusBadge = `
                    <span class="bg-green-200 px-2 py-1 rounded">
                        Match
                    </span>
                `;

            }else{

                statusBadge = `
                    <span class="bg-red-200 px-2 py-1 rounded">
                        Discrepancy
                    </span>
                `;

            }

            table.innerHTML += `

            <tr>

                <td class="border px-2 py-2"> 
                    ${item.product.name} 
                </td>

                <td class="border px-2 py-2">
                    ${item.system_stock}
                </td>

                <td class="border px-2 py-2">
                    ${item.physical_stock}
                </td>

                <td class="border px-2 py-2 d-none d-md-table-cell">
                    ${item.difference}
                </td>

                <td class="border px-2 py-2">
                    ${statusBadge}
                </td>

                <td class="border px-2 py-2 d-none d-md-table-cell">
                    
                    ${item.session.user.name} 
                </td>

            </tr>

            `;

        });

    });

}

function openCamera(){

    const html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(

        { facingMode: "environment" },

        {
            fps: 10,
            qrbox: 250
        },

        (decodedText) => {

            console.log(decodedText);

            // auto scan product
            scanProduct(decodedText);
            // stop camera setelah berhasil
            html5QrCode.stop();

        },

        (errorMessage) => {

   
            console.log(errorMessage+' '+ console.log(navigator.mediaDevices)+' '+console.log(navigator.mediaDevices?.getUserMedia));

        }

    );

}

function closeSession(){
    let btn =
        document.getElementById(
            'closeBtn'
        );

    btn.disabled = true;
    btn.innerText = 'Saving...';

    fetch(
        '/warehouse/stock-opname/close',
        {

            method:'POST',

            headers:{
                'Content-Type':'application/json',

                'X-CSRF-TOKEN':
                document.querySelector(
                    'meta[name="csrf-token"]'
                ).content
            },

            body: JSON.stringify({

                session_code:
                opnameSession

            })

        }

    )

    .then(res => res.json())

    .then(data => {

        alert(data.message);

        updateSessionLabel('CLOSED');

        location.reload();

    })
    .finally(()=>{

        btn.disabled = false;
        btn.innerText = 'Close Opname';

    });

}

function updateSessionLabel(status){

    document.getElementById(
        'session_code'
    ).innerText =
    opnameSession + ' (' + status + ')';

}

//dropbox load
function loadProducts(){

    fetch('/warehouse/products')

    .then(res => res.json())

    .then(response => {

        let data = response.data.data;

        let select =
            document.getElementById('product_id_drop');

        select.innerHTML =
            '<option value="">-- Pilih Produk --</option>';

        data.forEach(item => {

            select.innerHTML += `
                <option value="${item.id}">
                    ${item.name} - ${item.color} - ${item.size}
                </option>
            `;

        });

    });

}

function loadProductDetail(id){
    fetch('/warehouse/products/' + id)

    .then(res => res.json())

    .then(data => {

        let product = data.data;
  
        document.getElementById('product_sku').innerText = product.sku;
        document.getElementById('product_name').innerText = product.name;
        document.getElementById('product_stock').innerText = product.stock;
        document.getElementById('product_location').innerText =
        product.rack_slot?.rack?.rack_code + ' - ' + product.rack_slot?.slot_code;

    });
}


function selectProduct(){

    let id =
        document.getElementById(
            'product_id_drop'
        ).value;

    document.getElementById(
        'product_id'
    ).value = id;

    loadProductDetail(id);

}

loadProducts();
checkActiveSession();