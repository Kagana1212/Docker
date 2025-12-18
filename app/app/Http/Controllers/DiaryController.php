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
        // バリデーション（日付もチェック対象に入れる）
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'emotion' => 'required',
            'date' => 'required|date', // 日付が正しく送られているか確認
        ]);

        $diary = new \App\Models\Diary();
        $diary->title = $request->title;
        $diary->content = $request->content;
        $diary->emotion = $request->emotion;
        $diary->question_text = $request->question_text;
        $diary->question_answer = $request->question_answer;

        // 💡 重要：作成日時を、カレンダーで選んだ日付に強制的に設定する
        // 時刻が 00:00:00 にならないよう、現在時刻の「時:分:秒」を合わせると自然です
        $diary->created_at = $request->date . ' ' . now()->format('H:i:s');

        $diary->save();

        return redirect()->route('diary.index')->with('success', '日記を保存しました');
    }

    public function index()
    {
        // 作成日順に並べてページネーション
        $diaries = \App\Models\Diary::latest('created_at')->paginate(10);
        return view('diary.index', compact('diaries'));
    }

    // 2. カレンダー形式のメソッド（さっき作ったロジックをこちらへ）
    public function calendar(Request $request)
    {
        $yearMonth = $request->query('month', now()->format('Y-m'));
        $date = \Carbon\Carbon::parse($yearMonth . '-01');

        $diaries = \App\Models\Diary::whereYear('created_at', $date->year)
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
