<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日記カレンダー</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 py-10">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-lg font-bold text-gray-800">My Diary App</span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                    <span class="text-gray-600 text-sm">{{ Auth::user()->name }} さん</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 underline">
                            ログアウト
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <div class="max-w-4xl mx-auto px-4">

        {{-- ヘッダー：月移動と表示切り替え --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="flex items-center gap-6">
                <h1 class="text-3xl font-bold text-gray-800">{{ $date->format('Y年 n月') }}</h1>
                <div class="flex border rounded-lg overflow-hidden shadow-sm bg-white">
                    <a href="?month={{ $prevMonth->format('Y-m') }}"
                        class="px-4 py-2 hover:bg-gray-100 border-r text-gray-600 transition">←</a>
                    <a href="?month={{ now()->format('Y-m') }}"
                        class="px-4 py-2 hover:bg-gray-100 border-r text-sm font-medium text-gray-600 flex items-center">今月</a>
                    <a href="?month={{ $nextMonth->format('Y-m') }}"
                        class="px-4 py-2 hover:bg-gray-100 text-gray-600 transition">→</a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- 表示切り替えタブ --}}
                <div class="flex border rounded-lg overflow-hidden shadow-sm">
                    <span class="px-4 py-2 bg-blue-600 text-white text-sm font-medium">
                        📅 カレンダー
                    </span>
                    <a href="{{ route('diary.index') }}"
                        class="px-4 py-2 bg-white text-gray-600 hover:bg-gray-100 text-sm font-medium border-l">
                        📝 リスト
                    </a>
                </div>

                <a href="{{ route('diary.create') }}"
                    class="hidden md:block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md transition shadow-md">
                    + 書く
                </a>
            </div>
        </div>

        {{-- カレンダー本体 --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- 曜日ラベル --}}
            <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
                @foreach(['日', '月', '火', '水', '木', '金', '土'] as $i => $day)
                <div
                    class="py-3 text-center text-xs font-bold {{ $i == 0 ? 'text-red-500' : ($i == 6 ? 'text-blue-500' : 'text-gray-500') }}">
                    {{ $day }}
                </div>
                @endforeach
            </div>

            {{-- 日付グリッド --}}
            <div class="grid grid-cols-7">
                {{-- 前月の空白埋め --}}
                @for ($i = 0; $i < $firstDayOfWeek; $i++) <div
                    class="h-24 md:h-32 border-b border-r border-gray-100 bg-gray-50/30">
            </div>
            @endfor

            {{-- 日付セル --}}
            @for ($day = 1; $day <= $daysInMonth; $day++) @php $diary=$diaries->get($day);
                $currentLoopDate = $date->copy()->day($day);
                $isToday = $currentLoopDate->isToday();
                $isFuture = $currentLoopDate->isFuture();

                $style = $diary ? match($diary->emotion) {
                '喜' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'emoji' => '😊'],
                '怒' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'emoji' => '😡'],
                '悲' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'emoji' => '😢'],
                '楽' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'emoji' => '😆'],
                default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'emoji' => '😶'],
                } : null;
                @endphp

                <div
                    class="h-24 md:h-32 border-b border-r border-gray-100 p-2 relative group transition {{ $isFuture ? 'bg-gray-50/10' : 'hover:bg-blue-50/30' }}">
                    {{-- 日付番号 --}}
                    <div class="flex justify-between items-start">
                        <span
                            class="text-sm font-semibold {{ $isToday ? 'bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-full shadow-sm' : 'text-gray-400' }}">
                            {{ $day }}
                        </span>
                    </div>

                    @if($diary)
                    {{-- 【日記あり】編集へ --}}
                    <a href="{{ route('diary.edit', $diary->id) }}" class="block mt-2 h-full">
                        <div
                            class="{{ $style['bg'] }} {{ $style['text'] }} p-2 rounded-lg shadow-sm border border-white/50">
                            <div class="flex items-center gap-1">
                                <span class="text-base">{{ $style['emoji'] }}</span>
                                <span class="font-bold text-[10px] md:text-xs truncate">{{ $diary->title }}</span>
                            </div>
                            <div class="hidden md:block text-[10px] opacity-70 mt-1 line-clamp-2 leading-tight">
                                {{ $diary->content }}
                            </div>
                        </div>
                    </a>
                    @elseif(!$isFuture)
                    {{-- 【日記なし＆過去】新規作成へ（日付を渡す） --}}
                    <a href="{{ route('diary.create', ['date' => $currentLoopDate->format('Y-m-d')]) }}"
                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="bg-blue-100 text-blue-600 rounded-full p-2 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </a>
                    @endif
                </div>
                @endfor

                {{-- 翌月の空白埋め --}}
                @php $lastEmptyCells = (7 - ($firstDayOfWeek + $daysInMonth) % 7) % 7; @endphp
                @for ($i = 0; $i < $lastEmptyCells; $i++) <div
                    class="h-24 md:h-32 border-b border-r border-gray-100 bg-gray-50/30">
        </div>
        @endfor
    </div>
    </div>

    {{-- 下部：感情の凡例 --}}
    <div class="mt-8 flex flex-wrap justify-center gap-6">
        @foreach(['😊 喜' => 'bg-green-400', '😆 楽' => 'bg-yellow-400', '😢 悲' => 'bg-blue-400', '😡 怒' => 'bg-red-400']
        as $label => $color)
        <div class="flex items-center text-xs font-medium text-gray-500">
            <span class="w-3 h-3 {{ $color }} rounded-full mr-2"></span>
            {{ $label }}
        </div>
        @endforeach
    </div>
    </div>

</body>

</html>