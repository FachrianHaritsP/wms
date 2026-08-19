let productChart = null;
let stockChart = null;

function loadDashboard(){

    let period =
        document.getElementById(
            'period_filter'
        ).value;

    fetch(
        '/dashboard/data?period=' + period //'/api/warehouse/dashboard?period='
    )

    .then(res => res.json())

    .then(data => {

        // isi dashboard
        // KPI
        document.getElementById('total_products').innerText = data.kpi.total_products
        document.getElementById('total_stock').innerText = data.kpi.total_stock
        document.getElementById('stock_in').innerText = data.kpi.stock_in
        document.getElementById('stock_out').innerText = data.kpi.stock_out

        //low stock
        const lowStock = data.low_stock;
        const tbody = document.getElementById('low_stock_table');

        if (lowStock.total === 0) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-muted py-3">
                        Belum ada data stok rendah.
                    </td>
                </tr>
            `;

        } else {

            tbody.innerHTML = lowStock.data.map(item => `
                <tr>
                    <td class="text-wrap">${item.sku}</td>
                    <td class="text-wrap">${item.name}</td>
                    <td>${item.stock}</td>
                </tr>
            `).join('');
        }
        document.getElementById('lowStockCount').textContent =
        `Menampilkan ${lowStock.data.length} dari ${lowStock.total} produk`;

        if(data.product_movement.length === 0){
            document.getElementById('productMovementEmpty').style.display='block';
            document.getElementById('productChart').style.display='none';
        }else{
            document.getElementById('productMovementEmpty').style.display='none';
            document.getElementById('productChart').style.display='block';

            // render chart
            //product movement
            let productLabels = []
            let productData = []

            data.product_movement.forEach(item => {
                productLabels.push(item.name)
            productData.push(item.total_out)
            })

            if(productChart){

                productChart.destroy();

            }

            productChart = new Chart(document.getElementById('productChart'), {
                type: 'bar',
                    data: {
                    labels: productLabels,
                        datasets: [{
                        label: 'Product Movement',
                        data: productData
                        }]
                    }
            })

        }//end else

        if(data.stock_movement.length === 0){
            document.getElementById('stockMovementEmpty').style.display='block';
            document.getElementById('stockChart').style.display='none';
        }else{
            document.getElementById('stockMovementEmpty').style.display='none';
            document.getElementById('stockChart').style.display='block';

            //stock movement
            let dates = []
            let stockIn = []
            let stockOut = []

            data.stock_movement.forEach(item => {

                if (data.period === 'today') {

                    dates.push(
                        String(item.hour).padStart(2, '0') + ':00'
                    )

                } else {

                    dates.push(item.date)

                }

                stockIn.push(item.total_in)
                stockOut.push(item.total_out)
            })

            if(stockChart){

                stockChart.destroy();

            }
            stockChart = new Chart(document.getElementById('stockChart'),{
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

        }//end else

   
    });

}

loadDashboard();