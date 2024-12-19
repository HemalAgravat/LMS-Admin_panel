<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Borrowing Stats</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: flex-start;
            padding: 20px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .chart-container {
            width: 80%;
            height: 400px;
            margin-bottom: 20px;
        }
        select {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .chart-wrapper {
            display: flex;
            justify-content: flex-start;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>User Borrowing Statistics</h1>
        <select id="userLimit" onchange="updateChart()">
            <option value="3">Show Top 3</option>
            <option value="5">Show Top 5</option>
            <option value="10">Show Top 10</option>
        </select>
        <div class="chart-wrapper">
            <canvas id="userStatsChart" class="chart-container"></canvas>
        </div>
    </div>

    <script>
        const userStats = @json($userStats);
        let currentUserStats = userStats;
        const ctx = document.getElementById('userStatsChart').getContext('2d');
        let chart;
        function updateChart() {
            const limit = document.getElementById('userLimit').value;
            const limitedStats = currentUserStats.slice(0, limit);
            const labels = limitedStats.map(stat => 'User ' + stat.user_id);
            const data = limitedStats.map(stat => stat.borrowed_count);
            if (chart) {
                chart.destroy();
            }
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Books Borrowed',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Users'
                            }
                        }
                    },
                    plugins: {
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            font: {
                                weight: 'bold',
                                size: 16
                            },
                            color: 'black',
                            formatter: function(value) {
                                return value;
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }
        updateChart();
    </script>

</body>
</html>
