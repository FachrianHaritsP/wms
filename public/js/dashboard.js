fetch('/api/warehouse/dashboard')
.then(res => res.json())
.then(data => {

    // KPI
    document.getElementById('total_products').innerText = data.kpi.total_products
    document.getElementById('total_stock').innerText = data.kpi.total_stock
    document.getElementById('stock_in').innerText = data.kpi.stock_in
    document.getElementById('stock_out').innerText = data.kpi.stock_out

    // Low stock table
    let table = document.getElementById('low_stock_table')

    data.low_stock.forEach(item => {

        table.innerHTML += `
        <tr>
            <td>${item.sku}</td>
            <td>${item.name}</td>
            <td>${item.stock}</td>
        </tr>
        `
    })

    //product movement
    let productLabels = []
    let productData = []

    data.product_movement.forEach(item => {
         productLabels.push(item.name)
    productData.push(item.total_out)
    })

    new Chart(document.getElementById('productChart'), {
         type: 'bar',
            data: {
            labels: productLabels,
                datasets: [{
                label: 'Product Movement',
                data: productData
                }]
            }
    })
    
    //stock movement
    let dates = []
    let stockIn = []
    let stockOut = []

    data.stock_movement.forEach(item =>{
        dates.push(item.date)
        stockIn.push(item.total_in)
        stockOut.push(item.total_out)
    })

    new Chart(document.getElementById('stockChart'),{
        type: 'line',
        
        data :{
            labels : dates,
            datasets:[
                {
                    label: 'Stock In',
                    data: stockIn
                },
                {
                    label:'Stock Out',
                    data: stockOut
                }
            ]
        }
    })

})