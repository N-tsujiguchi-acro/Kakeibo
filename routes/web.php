<?php

use App\Http\Controllers\KakeiboController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController; // ← 追加！
use App\Http\Controllers\BudgetController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php

// 最初の「/」にアクセスしたら「/login」にリダイレクトするようにする


Route::middleware('auth')->group(function () {
   
    Route::get('/', [KakeiboController::class, 'index'])->name('kakeibo.index');

    Route::get('/create', [KakeiboController::class, 'create'])
        ->name('kakeibo.create');               // ← ここ

    Route::post('/store', [KakeiboController::class, 'store'])
        ->name('kakeibo.store');                // ← 追加推奨

    Route::get('/kakeibo/filter', [KakeiboController::class, 'filterByMonth'])
        ->name('kakeibo.filter');               // ← ここ

    Route::get('/chart', [KakeiboController::class, 'chart'])
        ->middleware('auth') 
        ->name('kakeibo.chart');                // ← ここ

    Route::get('/chart/category', [KakeiboController::class, 'categoryChart'])
        ->middleware('auth')
        ->name('kakeibo.categoryChart');        // ← ここ

    Route::delete('/delete/{id}', [KakeiboController::class, 'destroy'])
        ->name('kakeibo.destroy');              // ← ここ

    Route::get('/comment/create/{id}', [KakeiboController::class, 'createComment'])
        ->name('comment.create');               // ← ここ

    Route::post('/comment/store', [KakeiboController::class, 'storeComment'])
        ->name('comment.store');                // ← ここ

    // プロファイル周りは Breeze が自動で
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::get('/budgets/create', [BudgetController::class, 'create'])->name('budgets.create');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/budgets/{id}/edit', [BudgetController::class, 'edit'])->name('budgets.edit');
    Route::put('/budgets/{id}', [BudgetController::class, 'update'])->name('budgets.update');
});

require __DIR__.'/auth.php';
