<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiaryController;

// Route::get('/', function () { return view('welcome'); }); 
Route::get('/', [DiaryController::class, 'index'])->name('diary.index');
// 投稿画面
Route::get('/diary/create', [DiaryController::class, 'create'])->name('diary.create');
// 保存処理
Route::post('/diary/store', [DiaryController::class, 'store'])->name('diary.store');

Route::get('/diary/{id}/edit', [DiaryController::class, 'edit'])->name('diary.edit');

Route::put('/diary/{id}', [DiaryController::class, 'update'])->name('diary.update');

Route::delete('/diary/{id}', [DiaryController::class, 'destroy'])->name('diary.destroy');