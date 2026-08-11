let editingReturnId = null;

function resetCard(){
    document.getElementById('product_id').value = '';
    document.getElementById('qty').value = '';
    document.getElementById('reason').value = '';
    document.getElementById('notes').value = '';
}

function loadReturns(){

    fetch('api/returns') //pake api karena udh ada session dari login sebelumnya cuma pake web error

    .then(res => res.json())

    .then(data => {

    console.log(data);

    let tbody = document.getElementById('returnTable');

    tbody.innerHTML = '';

        data.data.data.forEach(item => {

            tbody.innerHTML += `
            
            <tr>

                <td>${item.product.name}</td>

                <td>${item.qty}</td>

                <td>${item.reason}</td>

                <td>${item.status}</td>

                <td>${item.user.name}</td>

            </tr>

            `;

        });

    })

}

function submitReturn() {

    let productId = document.getElementById('product_id').value;
    let qty = document.getElementById('qty').value;
    let reason = document.getElementById('reason').value;
    let notes = document.getElementById('notes').value;
    let btn =
        document.getElementById(
            'submitBtn'
        );

    btn.disabled = true;
    btn.innerText = 'Saving...';


    if(editingReturnId){

    updateReturn(
        editingReturnId,
        productId,
        qty,
        reason,
        notes
    );

    return;
    }   

    if(productId === ''){
        alert('Produk wajib dipilih');
        btn.disabled = false;
        btn.innerText = 'Submit Return';
        return;
    }

    if(qty === ''){
        alert('Qty wajib diisi');
        btn.disabled = false;
        btn.innerText = 'Submit Return';
        return;
    }

    if(parseInt(qty) <= 0){
        alert('Qty harus lebih dari 0');
        btn.disabled = false;
        btn.innerText = 'Submit Return';
        return;
    }

    if(reason.trim() === ''){
        alert('Reason wajib diisi');
        btn.disabled = false;
        btn.innerText = 'Submit Return';
        return;
    }

    fetch('/returns', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },

        body: JSON.stringify({
            product_id: productId,
            qty: qty,
            reason: reason,
            notes: notes,
        })

    })
    .then(res => res.json())//.then(res => res.json())
    .then(data => { //data => work ; async res=> buat tet

        console.log(data);

        if(!data.success){

            alert(data.message);

            return;

        }

        alert(data.message);
        resetCard();
        loadReturns();
        
    })
    .catch(err => {
        console.log(err);
        alert('Server tidak dapat dihubungi. atau\n' + ' '+err)
    })
    .finally(()=>{

        btn.disabled = false;
        btn.innerText = 'Submit Return';

    });

}

function updateReturn(
    id,
    productId,
    qty,
    reason,
    notes
    ){

    let btn =
        document.getElementById(
            'submitBtn'
        );

    btn.disabled = true;
    btn.innerText = 'Saving...';    

    fetch(

        '/returns/' + id,

        {

            method:'PUT',

            headers:{

                'Content-Type':'application/json',

                'X-CSRF-TOKEN':
                document.querySelector(
                    'meta[name="csrf-token"]'
                ).content

            },

            body: JSON.stringify({

                product_id: productId,
                qty: qty,
                reason: reason,
                notes: notes

            })

        }

    )

    .then(res => res.json())

    .then(data => {

        if(!data.success){

            alert(data.message);

            return;

        }

        alert(
            data.message
        );

        editingReturnId = null;

        resetCard();

        loadReturns(); 


    })
    .catch(err => { 

        console.log(err);
        alert('Server tidak dapat dihubungi. atau\n' + ' '+err);

    })
    .finally(()=>{

    let btn =
        document.getElementById(
            'submitBtn'
        );

    btn.disabled = false;
    btn.innerText = 'Submit Return';

    });

}

function cancelReturn(id){

    if(
        !confirm(
            'Yakin membatalkan return ini?'
        )
    ){
        return;
    }

    fetch(

        '/returns/' + id + '/cancel',

        {

            method:'POST',

            headers:{

                'Content-Type':'application/json',

                'X-CSRF-TOKEN':
                document.querySelector(
                    'meta[name="csrf-token"]'
                ).content

            }

        }

    )

    .then(res => res.json())

    .then(data => {

        if(!data.success){

            alert(data.message);

            return;

        }

        alert(data.message);

        loadReturns();

    })

    .catch(err => {

        console.log(err);

        alert(
            'Server tidak dapat dihubungi.'
        );

    });

}


function editReturn(id,productId,qty,reason, notes){

    document.getElementById('submitBtn').innerText ='Update Return';
    document.getElementById('returnsTitle').innerText ='Update';
    editingReturnId = id;

    // editingReturnId = null;

    // document.getElementById('submitBtn').innerText ='Submit Return';

    document.getElementById(
        'product_id'
    ).value = productId;

    document.getElementById(
        'qty'
    ).value = qty;

    document.getElementById(
        'reason'
    ).value = reason;

    document.getElementById(
        'notes'
    ).value = notes;

}