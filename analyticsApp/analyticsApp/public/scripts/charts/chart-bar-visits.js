const visitInfoUrl = `${apiUrl}pagevisits/${datePeriod}`;

let loadPageVisitChart = async () => {
    let visitInfoObj = await getAllData(visitInfoUrl);
    let ctx = document.getElementById('pageVisitChart').getContext('2d');
    let pageVisitChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            datasets: [{
                fill: true,
                label: 'visits',
                data: visitInfoObj[1],
                backgroundColor: `rgba(78,115,223,0.5)`,
                borderColor: `rgba(78,115,223,1)`,
                hoverBackgroundColor: `rgba(78,115,223,0.7)`,
                borderWidth: 2
            }],
            labels: visitInfoObj[0]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        stepSize: 1,
                    }
                }]
            },
            legend: {
                display: false
            }
        }
    });
};
