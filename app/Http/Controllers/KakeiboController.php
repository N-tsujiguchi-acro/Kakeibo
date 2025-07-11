<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kakeibo;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Budget;

class KakeiboController extends Controller
{
    //
    public function index()
    {
        $userId = Auth::id();
        [$items, $budgetSummaries] = $this->getItemsAndBudgetsByMonth($userId); // ← 月なし
        return view('index', compact('items', 'budgetSummaries'));
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

    public function chart(Request $request)
    {
        $userId = Auth::id();
        $selectedYear = $request->input('year', now()->year); // デフォルトは今年

        // 1〜12月を初期化
        $monthlyTotals = collect(range(1, 12))->mapWithKeys(function ($month) use ($selectedYear) {
            return [sprintf('%04d-%02d', $selectedYear, $month) => 0];
        });

        // DBから該当年の月別合計を取得
        $monthlyData = DB::table('kakeibos')
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->where('user_id', $userId)
            ->whereYear('date', $selectedYear)
            ->whereNull('deleted_at')  // ← ここで論理削除済みを除外
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 取得データをマージ（無い月は0円のまま）
        foreach ($monthlyData as $row) {
            $monthlyTotals[$row->month] = $row->total;
        }

        return view('chart', [
            'monthlyTotals' => $monthlyTotals,
            'selectedYear' => $selectedYear,
        ]);
    }


    public function categoryChart()
    {
        $userId = Auth::id(); // ログイン中のユーザーIDを取得
        $currentMonth = now()->format('Y-m'); // 例: '2025-07'

        // 当月のユーザーのカテゴリーごとの支出合計を取得（論理削除されていないレコードのみ）
        $data = DB::table('kakeibos')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->where('user_id', $userId)
            ->where('date', 'like', $currentMonth . '%')
            ->whereNull('deleted_at')  // ← ここで論理削除を除外
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


    public function filterByMonth(Request $request)
    {
        $userId = Auth::id();
        $month = $request->input('month'); // 例: '2025-07'

        [$items, $budgetSummaries] = $this->getItemsAndBudgetsByMonth($userId, $month); // ← 月あり
        return view('index', compact('items', 'budgetSummaries'));
    }


    private function getItemsAndBudgetsByMonth($userId, $month = null)
    {
        // 家計簿一覧（全件または月ごと）
        $itemsQuery = Kakeibo::with('category')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc');

        if ($month) {
            $itemsQuery->where('date', 'like', $month . '%');
        }

        $items = $itemsQuery->get();

        // カテゴリ別支出（常に今月分）
        $budgetMonth = $month ?? now()->format('Y-m');

        $spendPerCategory = Kakeibo::where('user_id', $userId)
            ->where('date', 'like', $budgetMonth . '%')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        // 予算データ取得
        $budgets = Budget::with('category')
            ->where('user_id', $userId)
            ->where('month', $budgetMonth)
            ->get();

        $budgetSummaries = [];

        foreach ($budgets as $budget) {
            $spent = $spendPerCategory[$budget->category_id] ?? 0;
            $budgetSummaries[] = [
                'category_name' => $budget->category->category_name ?? '未分類',
                'budget' => $budget->amount,
                'spent' => $spent,
            ];
        }

        return [$items, $budgetSummaries];
    }



}

