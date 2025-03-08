document.addEventListener("DOMContentLoaded", function () {
    fetch('chart-data.php')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('lineChart').getContext('2d');
            const lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels, 
                    datasets: [{
                        label: 'Performa',
                        data: data.data, 
                        borderColor: '#2563EB', 
                        backgroundColor: 'rgba(37, 99, 235, 0.2)', 
                        fill: true,
                        tension: 0.4, 
                        pointBackgroundColor: '#1E40AF',
                        pointBorderColor: '#2563EB',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        
                        y: {
                            title: {
                                display: true,
                                text: 'Jumlah'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching chart data:', error));
});
