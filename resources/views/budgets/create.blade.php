<!DOCTYPE html>
<html>
<head>
    <title>予算の作成</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>予算の作成</h1>

    <form action="{{ route('budgets.store') }}" method="POST">
        @csrf
        <p>
            <label>月（例：2025-07）:</label>
            <input type="month" name="month" value="{{ old('month') }}" required>
        </p>

        <p>
            <label>カテゴリ:</label>
            <select name="category_id">
                <option value="">--選択してください--</option>
                @foreach($categories as $id => $name)
                    <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </p>

        <p>
            <label>金額（円）:</label>
            <input type="number" name="amount" value="{{ old('amount') }}" required>
        </p>

        <p><button type="submit">登録</button></p>
    </form>

    <p><a href="{{ route('budgets.index') }}">← 戻る</a></p>
</body>
</html>
