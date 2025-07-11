<!DOCTYPE html>
<html>
<head>
    <title>家計簿</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <h1>家計簿一覧</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <x-responsive-nav-link :href="route('logout')"
                onclick="event.preventDefault();
                            this.closest('form').submit();">
            {{ __('Log Out') }}
        </x-responsive-nav-link>
    </form>
    <p><a href="{{ route('budgets.index') }}">＋ 予算を管理する</a></p>
    {{-- 登録ページへのリンク --}}
    <p><a href="{{ route('kakeibo.create') }}">＋ 新しい家計簿を追加</a></p>
    <p><a href="{{ route('kakeibo.chart') }}">📊 月別支出グラフを見る</a></p>

    <form method="GET" action="{{ route('kakeibo.filter') }}">
        <label>月を選択:
            <input type="month" name="month" value="{{ request('month') }}">
        </label>
        <button type="submit">絞り込み</button>
    </form>

    <ul>
        @foreach($items as $item)
            <li>
                {{ $item->date }}: {{ $item->title }} - ¥{{ $item->amount }}
                <form action="{{ route('kakeibo.destroy', $item->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button">削除</button>
                </form>

                {{-- コメントの表示 --}}
                @if(!empty($item->comment))
                    <br><strong>コメント:</strong> {{ $item->comment }}
                @else
                    <br><em style="color: gray;">コメントが追加できます！</em>
                @endif

                {{-- コメント追加ページへのリンク --}}
                <a href="{{ route('comment.create', $item->id) }}">コメントを追加</a>
            </li>
        @endforeach
    </ul>
     <h2>今月のカテゴリ別 予算状況</h2>
        <table border="1" cellpadding="8" style="margin-top: 20px;">
            <tr>
                <th>カテゴリ</th>
                <th>予算</th>
                <th>実支出</th>
                <th>残額</th>
                <th>範囲内</th>
            </tr>
            @foreach($budgetSummaries as $summary)
                <tr>
                    <td>{{ $summary['category_name'] }}</td>
                    <td>¥{{ number_format($summary['budget']) }}</td>
                    <td>¥{{ number_format($summary['spent']) }}</td>
                    <td>¥{{ number_format($summary['budget'] - $summary['spent']) }}</td>
                    <td>
                        @if ($summary['spent'] > $summary['budget'])
                            <span style="color: red;">⚠ 予算オーバー</span>
                        @else
                            <span style="color: green;">⭕ 範囲内</span>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>
</body>
</html>
