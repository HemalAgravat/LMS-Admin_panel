<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Return Stats</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }
        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 20px;
            color: #333;
        }
        .chart-container {
            width: 100%;
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .chart-container canvas {
            margin-top: 20px;
        }
        .tooltip {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-radius: 3px;
            padding: 5px;
        }
        @media (max-width: 768px) {
            h1 {
                font-size: 28px;
            }

            .chart-container {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Book Return Statistics</h1>

        <div class="chart-container">
            <canvas id="returnStatsChart" width="400" height="400"></canvas>
        </div>
    </div>

    <script>
        const returnStats = {
            returned: @json($returned),
            notReturned: @json($notReturned)
        };
        const ctx = document.getElementById('returnStatsChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Returned', 'Not Returned'],
                datasets: [{
                    data: [returnStats.returned, returnStats.notReturned],
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    borderColor: ['#fff', '#fff'],
                    borderWidth: 5

                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                            }
                        }

                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: {
                            size: 16
                        },
                        bodyFont: {
                            size: 14
                        },
                    }
                },
                cutout: '50%',
                maintainAspectRatio: false,
                aspectRatio: 1
            }
        });
    </script>
</body>
</html>
