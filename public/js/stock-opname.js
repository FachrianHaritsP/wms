let sessionCounter = 0;

let opnameSession =
    localStorage.getItem(
        'opname_session'
    );

if(opnameSession){

    document.getElementById(
        'opnameForm'
    ).style.display = 'block';

    document.getElementById(
        'session_code'
    ).innerText =
    opnameSession;
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

function refreshFill(){
    document.getElementById('physical_stock').value = '';
    document.getElementById('scanner').value = '';
    document.getElementById('product_sku').innerText = '';
    document.getElementById('product_name').innerText = '';
    document.getElementById('product_stock').innerText = '';
    document.getElementById('product_location').innerText = ''; 
    document.getElementById('scanner').focus();
}

function startOpname(){

    document.getElementById('opnameForm').style.display = 'block';
    if(!opnameSession){

    opnameSession =
        'OPN-' + Date.now();

    localStorage.setItem(
        'opname_session',
        opnameSession
    );

    document.getElementById(
        'session_code'
    ).innerText =
    opnameSession;
        
    console.log(opnameSession);

    }

    console.log(opnameSession);
    alert('Session Opname Started');

}


// scan product
function scanProduct(){

    
    let scanner = document.getElementById('scanner').value;
    //console.log(scanner);

    fetch('warehouse/scan/' + scanner)

    .then(res => res.json())

    .then(data => {

        console.log(data);

        // ambil product
        let product = data.data;
        //console.log(product);

        // set hidden id
        document.getElementById('product_id').value = product.id;

        // tampil info
        document.getElementById('product_sku').innerText = product.sku;
        document.getElementById('product_name').innerText = product.name;
        document.getElementById('product_stock').innerText = product.stock;
        document.getElementById('product_location').innerText =  document.getElementById('product_location').innerText = product.rack_slot.rack.rack_code + ' - ' + product.rack_slot.slot_code;//product.rack + ' - ' + product.slot; 

        //console.log(product.rack_slot.rack);
    })

    .catch(err => {

        console.log(err);

        alert('Product tidak ditemukan');

    });

}


function saveOpname(){

    let product_id = document.getElementById('product_id').value;
    let physical_stock = document.getElementById('physical_stock').value;

    fetch('/stock-opname', {

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

    });

}

function refreshOpnameHistory(){

    fetch(
    '/api/warehouse/stock-opname/history?session_code='
    + opnameSession)
    //fetch('/api/warehouse/stock-opname/history')

    .then(res => res.json())

    .then(data => {

        let table =
            document.getElementById(
                'history_table'
            );

        table.innerHTML = '';

        data.forEach(item => {

            document.getElementById(
                'counter'
            ).innerText =
            'Items Checked : ' + data.length;

             let statusBadge = '';

            if(item.status === 'match'){

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
                    ${item.user.name}
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

            // isi textbox
            document.getElementById('scanner').value =
                decodedText;

            // auto scan product
            scanProduct();

            // stop camera setelah berhasil
            html5QrCode.stop();

        },

        (errorMessage) => {

            // optional ignore
            console.log(errorMessage);

        }

    );

}

function closeSession(){

    fetch(
        '/stock-opname/close',
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

        localStorage.removeItem(
            'opname_session'
        );

        location.reload();

    });

}

// detect enter scanner
document.getElementById('scanner').addEventListener('keypress', function(e){

    if(e.key === 'Enter'){

        e.preventDefault();

        scanProduct();

    }

});
