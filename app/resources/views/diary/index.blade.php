<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日記一覧 - リスト表示</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 py-10">

    <div class="max-w-4xl mx-auto px-4">
        
        {{-- ヘッダーセクション --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">📝 過去の日記</h1>
            
            <div class="flex items-center gap-4">
                {{-- 表示切り替えタブ --}}
                <div class="flex border rounded-lg overflow-hidden shadow-sm">
                    <a href="{{ route('diary.calendar') }}" class="px-4 py-2 bg-white text-gray-600 hover:bg-gray-100 text-sm font-medium border-r">
                        📅 カレンダー
                    </a>
                    <span class="px-4 py-2 bg-blue-600 text-white text-sm font-medium">
                        📝 リスト
                    </span>
                </div>

                <a href="{{ route('diary.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md transition shadow-md">
                    + 新しく書く
                </a>
            </div>
        </div>

        @if($diaries->isEmpty())
            <div class="bg-white p-12 rounded-xl shadow text-center text-gray-500 border border-dashed border-gray-300">
                <p class="text-lg">まだ日記がありません。</p>
                <p class="text-sm">最初の日記を書いて、思い出を記録しましょう！</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($diaries as $diary)
                    {{-- 感情ごとのデザイン設定 --}}
                    @php
                        $style = match($diary->emotion) {
                            '喜' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-500', 'emoji' => '😊'],
                            '怒' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-500', 'emoji' => '😡'],
                            '悲' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-500', 'emoji' => '😢'],
                            '楽' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-500', 'emoji' => '😆'],
                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-500', 'emoji' => '😶'],
                        };
                    @endphp

                    {{-- 日記カード --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-8 {{ $style['border'] }} hover:shadow-md transition duration-300 overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-medium text-gray-500">{{ $diary->created_at->format('Y/m/d (D)') }}</span>
                                        <span class="text-xs text-gray-400">{{ $diary->created_at->format('H:i') }}</span>
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-800">{{ $diary->title }}</h2>
                                </div>
                                
                                {{-- 感情バッジ --}}
                                <div class="px-4 py-1.5 rounded-full flex items-center {{ $style['bg'] }} {{ $style['text'] }} font-bold text-sm">
                                    <span class="mr-2 text-lg">{{ $style['emoji'] }}</span>
                                    {{ $diary->emotion }}
                                </div>
                            </div>
                            
                            {{-- 本文 --}}
                            <p class="text-gray-700 whitespace-pre-wrap leading-relaxed mb-6">{{ Str::limit($diary->content, 200) }}</p>
                            
                            {{-- 365日の質問セクション --}}
                            @if($diary->question_text) {{-- 質問文が存在する場合のみ表示 --}}
                                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400 mb-6 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Daily Question</span>
                                    </div>
                                    
                                    {{-- 質問文（ここを表示させたい内容） --}}
                                    <p class="text-sm text-gray-700 font-bold mb-2">
                                        <span class="text-blue-600 mr-1">Q:</span>{{ $diary->question_text }}
                                    </p>

                                    {{-- 回答内容 --}}
                                    <div class="bg-white/60 p-3 rounded border border-blue-100">
                                        <p class="text-sm text-gray-600 leading-relaxed">
                                            <span class="text-gray-400 mr-1 font-bold">A:</span>{{ $diary->question_answer ?: '（回答なし）' }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            {{-- 操作ボタン --}}
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('diary.edit', $diary->id) }}" class="flex items-center gap-1 text-sm font-medium text-green-600 hover:text-green-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    編集する
                                </a>

                                <form action="{{ route('diary.destroy', $diary->id) }}" method="POST" onsubmit="return confirm('思い出を削除してもよろしいですか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center gap-1 text-sm font-medium text-red-500 hover:text-red-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        削除
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ページネーション --}}
            <div class="mt-12">
                {{ $diaries->links() }}
            </div>
        @endif
    </div>

</body>
</html>