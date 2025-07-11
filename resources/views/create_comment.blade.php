
<!DOCTYPE html>
<html>
<head>
    <title>コメント追加</title>
    <style>
        body {
            font-family: "Helvetica Neue", sans-serif;
            background-color: #f5f5f5;
            color: #333;
            padding: 20px;
        }

        h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 15px;
        }

        strong {
            color: #27ae60;
        }

        label {
            font-weight: bold;
        }

        textarea {
            width: 100%;
            max-width: 400px;
            padding: 8px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #2980b9;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>コメントを追加</h1>

    <p><strong>日付：</strong>{{ $kakeibo->date }}</p>
    <p><strong>項目名：</strong>{{ $kakeibo->title }}</p>
    <p><strong>金額：</strong>¥{{ $kakeibo->amount }}</p>
    <p><strong>カテゴリー：</strong>{{ optional($kakeibo->category)->category_name ?? '未設定' }}</p>

    <form method="POST" action="/comment/store">
        @csrf
        <input type="hidden" name="kakeibo_id" value="{{ $kakeibo->id }}">

        <p>
            <label for="comment">コメント:</label><br>
            <textarea name="comment" id="comment" rows="4" cols="40">{{ old('comment') }}</textarea>
        </p>

        <p><button type="submit">保存</button></p>
    </form>
    <p><a href="/">一覧に戻る</a></p>
</body>
</html>
