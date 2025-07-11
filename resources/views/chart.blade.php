<!DOCTYPE html>
<html>
<head>
    <title>月別支出グラフ</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>月別支出グラフ</h1>
    <canvas id="expenseChart" width="600" height="400"></canvas>

    <script>
        const ctx = document.getElementById('expenseChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyData->pluck('month')) !!},
                datasets: [{
                    label: '支出合計 (円)',
                    data: {!! json_encode($monthlyData->pluck('total')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>

    <p><a href="/">一覧に戻る</a></p>
    <p><a href="{{ route('kakeibo.categoryChart') }}">カテゴリー別表示へ切り替え</a></p>

</body>
</html>
