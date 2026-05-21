function submitReturn() {

    let productId = document.getElementById('product_id').value;
    let qty = document.getElementById('qty').value;
    let reason = document.getElementById('reason').value;
    let notes = document.getElementById('notes').value;

    fetch('/api/returns', {
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
    .then(response => {

        let data = response.data;

        console.log(data);

        alert('Return berhasil ditambahkan');

        // reset form
        document.getElementById('qty').value = 1;
        document.getElementById('reason').value = '';

    })
    .catch(err => {
        console.log(err);
        alert('Terjadi error');
    });

}
