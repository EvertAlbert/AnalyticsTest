const activitiesPerHourUrl = `${apiUrl}eventsPerHour/${datePeriod}`;

let loadActivityChart = async () => {
    let activitiesPerHourObj = await getAllData(activitiesPerHourUrl);
    let ctx = document.getElementById('activityChart').getContext('2d');
    let productViewChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: activitiesPerHourObj[0],
            datasets: [{
                fill: true,
                data: activitiesPerHourObj[1],
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
