
function resetCard(){
    document.getElementById('product_id').value = '';
    document.getElementById('qty').value = '';
    document.getElementById('reason').value = '';
    document.getElementById('notes').value = '';
}

function loadReturns(){

    fetch('/returns/data')

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

        //let data = response.data; //work
        //let text = await res.text();
        console.log(data);

        if(data.success){
            alert('Return berhasil ditambahkan');
             // reset form
            resetCard();
            loadReturns();
        }
       
        
    })
    .catch(err => {
        console.log(err);
        alert('Terjadi error');
    });

}

