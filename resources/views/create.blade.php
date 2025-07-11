<!DOCTYPE html>
<html>
<head>
    <title>家計簿登録</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        
    </style>
</head>
<body>
    <h1>家計簿を追加</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/store">
        @csrf
        <p>日付: <input type="date" name="date" value="{{ old('date') }}"></p>
        <p>項目名: <input type="text" name="title" value="{{ old('title') }}"></p>
        <p>金額: <input type="number" name="amount" value="{{ old('amount') }}"></p>

        <p>カテゴリー:
            <select name="category_id">
                <option value="">選択してください</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
        </p>

        <p><button type="submit">登録</button></p>
    </form>


    <p><a href="/">一覧に戻る</a></p>
</body>
</html>
