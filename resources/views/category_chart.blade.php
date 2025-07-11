<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>カテゴリー別グラフ</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>当月カテゴリー別支出合計グラフ</h1>

    <canvas id="categoryChart" width="400" height="400"></canvas>

    <script>
        // PHPから渡されたデータをJavaScriptに渡す
        const labels = @json($chartData['labels']);
        const data = @json($chartData['totals']);

        const ctx = document.getElementById('categoryChart').getContext('2d');

        const categoryChart = new Chart(ctx, {
            type: 'pie', // 円グラフ
            data: {
                labels: labels,
                datasets: [{
                    label: 'カテゴリー別支出',
                    data: data,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#8A2BE2',
                        '#00CED1',
                        '#FF7F50',
                        '#90EE90',
                        '#FFD700'
                    ],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ¥' + context.parsed.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
    <p><a href="/">一覧に戻る</a></p>
</body>
</html>
