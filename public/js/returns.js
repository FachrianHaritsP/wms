let editingReturnId = null;

function resetCard(){
    document.getElementById('product_id').value = '';
    document.getElementById('qty').value = '';
    document.getElementById('reason').value = '';
    document.getElementById('notes').value = '';
}

function loadReturns(page = 1) {

     //console.log('LOAD RETURNS RUNNING', page);
    fetch('/returns/data?page=' + page)

    .then(res => res.json())
    .then(data => {

        let tbody =
            document.getElementById('returnTable');

        tbody.innerHTML = '';

        if (data.data.data.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="6"
                            class="text-center text-muted py-3">
                            Belum ada data return.
                        </td>
                    </tr>
                `;

                } else {


        data.data.data.forEach(item => {

            let statusBadge = `
                <span class="badge bg-warning text-dark">
                    Pending
                </span>
            `;

            tbody.innerHTML += `
                <tr>

                    <td>
                        ${item.product.name}
                    </td>

                    <td>
                        ${item.qty}
                    </td>

                    <td>
                        ${item.reason}
                    </td>

                    <td class="d-none d-md-table-cell">
                        ${statusBadge}
                    </td>

                    <td class="d-none d-md-table-cell">
                        ${item.user.name}
                    </td>

                    <td>

                        <div class="d-flex flex-column gap-1">

                            <button
                                class="btn btn-warning btn-sm"
                                onclick="editReturn(
                                    ${item.id},
                                    ${item.product_id},
                                    ${item.qty},
                                    '${item.reason}',
                                    '${item.notes ?? ''}'
                                )">
                                Edit
                            </button>

                            <button
                                class="btn btn-danger btn-sm"
                                onclick="cancelReturn(${item.id})">
                                Cancel
                            </button>

                        </div>

                    </td>

                </tr>
            `;

        });
        }
        renderReturnPagination(data.data);

    });


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

    if(reason === ''){
        alert('Alasan return wajib dipilih');
        btn.disabled = false;
        btn.innerText = 'Submit Return';
        return;
    }

    if(reason === 'Lainnya' && notes.trim() === ''){
        alert('Penjelasan wajib diisi jika memilih Lainnya');
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

function renderReturnPagination(meta){

    let container =
        document.getElementById('returnPagination');

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
            onclick="loadReturns(${meta.current_page - 1})">
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
                onclick="loadReturns(${page})">
                ${page}
            </button>
        `;
    }

    html += `
        <button
            class="btn btn-outline-primary btn-sm"
            ${meta.current_page === meta.last_page ? 'disabled' : ''}
            onclick="loadReturns(${meta.current_page + 1})">
            Next
        </button>
    `;

    html += `</div>`;

    container.innerHTML = html;
}

loadReturns();