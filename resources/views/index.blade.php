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
</body>
</html>
