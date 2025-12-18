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
        $diaries = \App\Models\Diary::latest()->get();

        return view('diary.index', compact('diaries'));
    }
    // 編集画面を表示
    public function edit($id)
    {
        $diary = Diary::findOrFail($id);
        return view('diary.edit', compact('diary'));
    }

    // データを更新
    public function update(Request $request, $id)
    {
        $diary = \App\Models\Diary::findOrFail($id);
        
        // バリデーション（必要であれば）
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        // データを更新
        $diary->update($request->all());

        // 一覧画面に戻る
        return redirect()->route('diary.index');
    }

    // データを削除
    public function destroy($id)
    {
        $diary = \App\Models\Diary::findOrFail($id);

        $diary->delete();

        return redirect()->route('diary.index')->with('success', '日記を削除しました');
    }
}
