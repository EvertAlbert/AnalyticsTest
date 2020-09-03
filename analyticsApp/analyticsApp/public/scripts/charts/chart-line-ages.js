const ageDataUrl = `${apiUrl}agedata/${datePeriod}`;

let loadAgeChart = async () => {
    let ageDataObj = await getAllData(ageDataUrl);
    let ctx = document.getElementById('ageChart').getContext('2d');
    let ageChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                fill: true,
                label: 'ages',
                lineTension: 0.2,
                backgroundColor: `rgba(220,53,69,0.05)`,
                borderColor: `rgba(220,53,69,1)`,
                pointRadius: 3,
                pointBackgroundColor: `rgba(220,53,69,1)`,
                pointBorderColor: `rgba(220,53,69,1)`,
                data: ageDataObj[1]
            }],
            labels: ageDataObj[0]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        stepSize: 1,
                        maxTicksLimit: 10,
                    }
                }]
            },
            legend: {
                display: false
            }
        }
    });
};
