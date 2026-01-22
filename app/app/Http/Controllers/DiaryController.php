<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiaryController extends Controller
{
    // 投稿画面を表示する
    public function create(Request $request)
    {
        // URLから日付を取得（なければ今日の日付）
        $selectedDate = $request->query('date', now()->format('Y-m-d'));
        
        // "2023-12-18" から "12-18" を抽出
        $dateMd = date('m-d', strtotime($selectedDate));

        // その日の質問を取得
        $question = \App\Models\Question::where('date_md', $dateMd)->first();
        
        // 質問がなければデフォルトメッセージ
        $questionText = $question ? $question->question_text : '今日という一日はどうでしたか？';

        return view('diary.create', compact('questionText', 'selectedDate'));
    }

    // 投稿内容をDBに保存する
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'emotion' => 'required',
            'date' => 'required|date',
        ]);

        // auth()->id() で現在ログイン中のユーザーIDを取得してセット
        $diary = new \App\Models\Diary();
        $diary->user_id = auth()->id();
        $diary->title = $request->title;
        $diary->content = $request->content;
        $diary->emotion = $request->emotion;
        $diary->question_text = $request->question_text;
        $diary->question_answer = $request->question_answer;
        $diary->created_at = $request->date . ' ' . now()->format('H:i:s');

        $diary->save();

        return redirect()->route('diary.index')->with('success', '日記を保存しました');
    }

    public function index()
    {
        // 💡 auth()->user()->diaries() を使うことで、自分の日記だけに限定されます
        $diaries = auth()->user()->diaries()->latest('created_at')->paginate(10);
        return view('diary.index', compact('diaries'));
    }

    // カレンダー表示
    public function calendar(Request $request)
    {
        $yearMonth = $request->query('month', now()->format('Y-m'));
        $date = \Carbon\Carbon::parse($yearMonth . '-01');

        // 💡 クエリの先頭を auth()->user()->diaries() に変更
        $diaries = auth()->user()->diaries()
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->get()
                        ->keyBy(function($d) {
                            return $d->created_at->day;
                        });

        $daysInMonth = $date->daysInMonth;
        $firstDayOfWeek = $date->dayOfWeek;
        $prevMonth = $date->copy()->subMonth();
        $nextMonth = $date->copy()->addMonth();

        return view('diary.calendar', compact('diaries', 'date', 'daysInMonth', 'firstDayOfWeek', 'prevMonth', 'nextMonth'));
    }

    // 編集画面を表示
    public function edit($id)
    {
        // 💡 自分の日記の中にそのIDがあるか探す。なければ404エラー。
        $diary = auth()->user()->diaries()->findOrFail($id);
        return view('diary.edit', compact('diary'));
    }

    public function update(Request $request, $id)
    {
        $diary = auth()->user()->diaries()->findOrFail($id);
        
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
        $diary = auth()->user()->diaries()->findOrFail($id);
        $diary->delete();

        return redirect()->route('diary.index')->with('success', '日記を削除しました');
    }
}