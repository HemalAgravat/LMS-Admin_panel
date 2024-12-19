<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publication Year Stats - Line Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        .chart-container {
            width: 80%;
            max-width: 1000px;
            margin-top: 20px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        canvas {
            width: 100% !important;
            height: 500px !important;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="chart-container">
        <h1>Books Published Over the Years</h1>
        <canvas id="publicationYearChart"></canvas>
    </div>

    <script>
        const publicationStats = @json($stats);
        console.log('Publication Stats:', publicationStats);
        const labels = publicationStats.map(stat => stat.year);
        const data = publicationStats.map(stat => stat.count);
        const ctxLine = document.getElementById('publicationYearChart').getContext('2d');
        const lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Books Published',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    pointHoverRadius: 10,
                    pointHoverBackgroundColor: 'rgba(255, 99, 132, 1)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                        },
                        title: {
                            display: true,
                            text: 'Books Published'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Year'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const year = tooltipItem.label;
                                const bookCount = tooltipItem.raw;
                                return `${year}: ${bookCount} Books`;
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
