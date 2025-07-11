<!DOCTYPE html>
<html>
<head>
    <title>予算一覧</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>予算一覧</h1>

    <p><a href="{{ route('budgets.create') }}">＋ 予算を追加</a></p>
    <table border="1">
        <tr>
            <th>月</th>
            <th>カテゴリ</th>
            <th>金額</th>
            <th>操作</th>
        </tr>
        @foreach($budgets as $budget)
            <tr>
                <td>{{ $budget->month }}</td>
                <td>{{ $budget->category->category_name ?? '未設定' }}</td>
                <td>¥{{ number_format($budget->amount) }}</td>
                <td>
                    <a href="{{ route('budgets.edit', $budget->id) }}">編集</a>
                </td>
            </tr>
        @endforeach
    </table>
    <p><a href="/">← 戻る</a></p>
</body>
</html>
