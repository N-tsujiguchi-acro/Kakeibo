<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kakeibo;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KakeiboController extends Controller
{
    //
    public function index()
    {
        $userId = Auth::id(); // ログインユーザーのIDを取得

        // ログインユーザーのデータだけ取得
        $items = Kakeibo::where('user_id', $userId)
                        ->orderBy('date', 'desc')
                        ->get();

        return view('index', compact('items'));
    }

    public function create(){
        
       $categories = Category::all();  // すべてのカテゴリーを取得

        return view('create', compact('categories'));
    }

    public function store(Request $request){
        //バリデーション
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'amount' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',  // 追加
        ]);

        //保存
        Kakeibo::create([
            'date' => $request->date,
            'title' => $request->title,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(), // ★ ここを忘れずに！
        ]);

        return redirect('/')->with('success','登録しました！');

    }

    public function createComment($kakeibo_id)
    {
        $kakeibo = Kakeibo::with('category')->findOrFail($kakeibo_id); // ← 追記！

        return view('create_comment', compact('kakeibo'));
    }


    public function storeComment(Request $request)
    {
        $request->validate([
            'kakeibo_id' => 'required|exists:kakeibos,id',
            'comment' => 'nullable|string|max:1000',
        ]);

        $kakeibo = Kakeibo::findOrFail($request->kakeibo_id);
        $kakeibo->comment = $request->comment;
        $kakeibo->save();

        return redirect('/')->with('success', 'コメントを保存しました！');
    }

    // app/Http/Controllers/KakeiboController.php

    public function destroy($id)
    {
        $kakeibo = Kakeibo::findOrFail($id);
        $kakeibo->delete();  // 論理削除(ソフトデリート)実行

        return redirect('/')->with('success', '家計簿を削除しました。');
    }

    public function chart()
    {
        $userId = Auth::id(); // ← ログイン中のユーザーIDを取得

        $monthlyData = DB::table('kakeibos')
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->where('user_id', $userId) // ★ ログイン中のユーザーだけに限定
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('chart', compact('monthlyData'));
    }

    public function categoryChart()
    {
        $userId = Auth::id(); // ログイン中のユーザーIDを取得

        // このユーザーのカテゴリーごとの支出合計を取得
        $data = DB::table('kakeibos')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->where('user_id', $userId) // ← ★ユーザーで絞り込み！
            ->groupBy('category_id')
            ->get();

        // カテゴリー名を取得（ID => 名前の連想配列）
        $categories = DB::table('categories')->pluck('category_name', 'id');

        // グラフ用のデータ整形
        $chartData = [
            'labels' => [],
            'totals' => [],
        ];

        foreach ($data as $row) {
            $chartData['labels'][] = $categories[$row->category_id] ?? '未分類';
            $chartData['totals'][] = $row->total;
        }

        return view('category_chart', compact('chartData'));
    }

    //家計簿一覧を月で絞り込むとき
    public function filterByMonth(Request $request)
    {
        //dd($request->query());
        
        $month = $request->input('month'); // 例: '2025-07'
      


        // 指定月のデータ取得
        $items = Kakeibo::where('date', 'like', $month . '%')->get();

        return view('index', compact('items'));
    }


}

