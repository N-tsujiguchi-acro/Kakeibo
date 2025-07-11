<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Budget;
use App\Models\Category;

class BudgetController extends Controller
{
    // 予算一覧表示
    public function index()
    {
        $budgets = Budget::where('user_id', Auth::id())->with('category')->get();
        return view('budgets.index', compact('budgets'));
    }

    // 予算作成フォーム表示
    public function create()
    {
        $categories = Category::pluck('category_name', 'id');
        return view('budgets.create', compact('categories'));
    }

    // 予算登録処理
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|integer|min:0',
        ]);

        Budget::create([
            'user_id' => Auth::id(),
            'month' => $request->month,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
        ]);

        return redirect()->route('budgets.index')->with('success', '予算を登録しました。');
    }

    // 編集フォーム表示
    public function edit($id)
    {
        $budget = Budget::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $categories = Category::pluck('category_name', 'id');
        return view('budgets.edit', compact('budget', 'categories'));
    }

    // 更新処理
    public function update(Request $request, $id)
    {
        $budget = Budget::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'month' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|integer|min:0',
        ]);

        $budget->update([
            'month' => $request->month,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
        ]);

        return redirect()->route('budgets.index')->with('success', '予算を更新しました。');
    }
}
