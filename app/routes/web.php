<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\ProfileController;

Route::get('/dashboard', function () {
    return redirect()->route('diary.calendar');
})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    
    // カレンダー画面
    Route::get('/calendar', [DiaryController::class, 'calendar'])->name('diary.calendar');
    
    // 一覧画面
    Route::get('/diary', [DiaryController::class, 'index'])->name('diary.index');
    
    // 作成・保存
    Route::get('/diary/create', [DiaryController::class, 'create'])->name('diary.create');
    Route::post('/diary/store', [DiaryController::class, 'store'])->name('diary.store');
    
    // 編集・更新・削除
    Route::get('/diary/{id}/edit', [DiaryController::class, 'edit'])->name('diary.edit');
    Route::put('/diary/{id}', [DiaryController::class, 'update'])->name('diary.update');
    Route::delete('/diary/{id}', [DiaryController::class, 'destroy'])->name('diary.destroy');

    // プロフィール（Breeze標準）
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// トップページにアクセスしたらカレンダーへ飛ばす
Route::get('/', function () {
    return redirect()->route('diary.calendar');
});

require __DIR__.'/auth.php';