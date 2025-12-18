<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    // 投稿画面を表示する
    public function create()
    {
        return view('diary.create');
    }

    // 投稿内容をDBに保存する
    public function store(Request $request)
    {

        //dd($request->all());
        // バリデーション（入力チェック）
        $validated = $request->validate([
            'title' => 'required|max:50',
            'content' => 'required',
            'emotion' => 'required',
            'question_answer' => 'nullable',
        ]);

        // 保存実行
        \App\Models\Diary::create($validated);

        // トップへ戻る
        return redirect('/')->with('message', '日記を保存しました！');
    }
    public function index()
    {
        $diaries = Diary::latest()->get(); // 新しい順に日記を取得
        return view('diary.index', compact('diaries'));
    }
}
