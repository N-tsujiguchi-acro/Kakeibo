<!DOCTYPE html>
<html>
<head>
    <title>月別支出グラフ</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>{{ $selectedYear }}年の月別支出グラフ</h1>

    {{-- 年選択フォーム --}}
    <form method="GET" action="{{ route('kakeibo.chart') }}">
        <label for="year">年を選択：</label>
        <select name="year" id="year" onchange="this.form.submit()">
            @for ($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>

    <canvas id="expenseChart" width="600" height="400"></canvas>

    <script>
        const ctx = document.getElementById('expenseChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($monthlyTotals->toArray())) !!},
                datasets: [{
                    label: '支出合計 (円)',
                    data: {!! json_encode(array_values($monthlyTotals->toArray())) !!},
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
    <p><a href="{{ route('kakeibo.categoryChart') }}">当月のカテゴリー別表示へ</a></p>
</body>
</html>
