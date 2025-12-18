<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日記一覧</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 py-10">

    <div class="max-w-4xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">📖 過去の日記</h1>
            <a href="{{ route('diary.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-200">
                + 新しく書く
            </a>
        </div>

        @if($diaries->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow text-center text-gray-500">
                まだ日記がありません。最初の日記を書いてみましょう！
            </div>
        @else
            <div class="grid gap-6">
                @foreach ($diaries as $diary)
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow relative">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="text-sm text-gray-500">{{ $diary->created_at->format('Y/m/d H:i') }}</span>
                                <h2 class="text-xl font-bold text-gray-800">{{ $diary->title }}</h2>
                            </div>
                            <span class="text-2xl" title="気分">{{ $diary->emotion }}</span>
                        </div>
                        
                        <p class="text-gray-700 whitespace-pre-wrap mb-4">{{ Str::limit($diary->content, 100) }}</p>
                        
                        @if($diary->question_answer)
                            <div class="bg-yellow-50 p-3 rounded text-sm text-gray-600 border-l-4 border-yellow-400 mb-4">
                                <strong>Q: 一番おいしかったものは？</strong><br>
                                {{ $diary->question_answer }}
                            </div>
                        @endif

                        <div class="flex justify-end gap-2 border-t pt-4">
                            <a href="{{ route('diary.edit', $diary->id) }}" class="text-sm bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded transition">
                                編集
                            </a>

                            <form action="{{ route('diary.destroy', $diary->id) }}" method="POST" 
                                onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE') <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded transition">
                                    削除
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>