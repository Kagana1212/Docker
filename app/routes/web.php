<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiaryController;

// Route::get('/', function () { return view('welcome'); }); 
Route::get('/', [DiaryController::class, 'index'])->name('diary.index');
// 投稿画面
Route::get('/diary/create', [DiaryController::class, 'create'])->name('diary.create');
// 保存処理
Route::post('/diary/store', [DiaryController::class, 'store'])->name('diary.store');

