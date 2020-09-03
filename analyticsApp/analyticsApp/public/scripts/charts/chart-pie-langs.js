const langDataUrl = `${apiUrl}langdata/${datePeriod}`;

let loadLangChart = async () => {
    let langDataObj = await getAllData(langDataUrl);

    let ctx = document.getElementById('langChart').getContext('2d');
    let chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'doughnut',
        // The data for our dataset
        data: {
            labels: langDataObj[0],
            datasets: [{
                data: langDataObj[1],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#E74A3B'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#E74A3B'],
                hoverBorderColor: "rgba(234, 236, 244)",
            }]
        },
        options: {
            maintainAspectRatio: true, //set to false to enlarge
            legend: {
                position: 'right'
            }
        }
    });
};
