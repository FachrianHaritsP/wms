let sessionCounter = 0;

function startOpname(){

    document.getElementById('opnameForm').style.display = 'block';

    opnameSession =
        'OPN-' + Date.now();

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
    let physical_stock =
        document.getElementById('physical_stock').value;

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
        loadOpnameHistory();
        sessionCounter++;
        document.getElementById('counter').innerText = sessionCounter;

    })

    .catch(err => {

        console.log(err);

        alert('Terjadi error');

    });

}

function loadOpnameHistory(){

    fetch('/stock-opname/data')

    .then(res => res.json())

    .then(data => {

        console.log(data);

    });

}

function openCamera(){

    const html5QrCode =
        new Html5Qrcode("reader");

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
            // console.log(errorMessage);

        }

    );

}

// detect enter scanner
document.getElementById('scanner')
.addEventListener('keypress', function(e){

    if(e.key === 'Enter'){

        e.preventDefault();

        scanProduct();

    }

});