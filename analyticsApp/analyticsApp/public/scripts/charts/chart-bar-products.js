const productViewsUrl = `${apiUrl}productViews/${datePeriod}`;

let loadProductChart = async () => {
    let productViewsObj = await getAllData(productViewsUrl);
    let ctx = document.getElementById('productViewChart').getContext('2d');
    let productViewChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: productViewsObj[0],
            datasets: [{
                fill: true,
                data: productViewsObj[1],
                backgroundColor: `rgba(54,185,204,0.5)`,
                borderColor: `rgba(54,185,204,1)`,
                hoverBackgroundColor: `rgba(54,185,204,0.7)`,
                borderWidth: 2
            }],
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        stepSize: 1,
                        maxTicksLimit: 5,
                    }
                }]
            },
            legend: {
                display: false
            }
        }
    });
};
