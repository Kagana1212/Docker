<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日記を編集</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 py-10">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">📝 日記を編集する</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('diary.update', $diary->id) }}" method="POST">
            @csrf
            @method('PUT') <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">タイトル</label>
                <input type="text" name="title" id="title" value="{{ old('title', $diary->title) }}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="emotion" class="block text-sm font-medium text-gray-700">今の気分</label>
                <select name="emotion" id="emotion" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="喜" {{ old('emotion', $diary->emotion) == '喜' ? 'selected' : '' }}>😊 喜（うれしい）</option>
                    <option value="怒" {{ old('emotion', $diary->emotion) == '怒' ? 'selected' : '' }}>😡 怒（おこ）</option>
                    <option value="悲" {{ old('emotion', $diary->emotion) == '悲' ? 'selected' : '' }}>😢 悲（かなしい）</option>
                    <option value="楽" {{ old('emotion', $diary->emotion) == '楽' ? 'selected' : '' }}>😆 楽（たのしい）</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">本文</label>
                <textarea name="content" id="content" rows="5" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500" required>{{ old('content', $diary->content) }}</textarea>
            </div>

            <div class="mb-6">
                <label for="question_answer" class="block text-sm font-medium text-gray-700">今日の質問：一番おいしかったものは？</label>
                <textarea name="question_answer" id="question_answer" rows="2" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2 bg-yellow-50 focus:ring-blue-500 focus:border-blue-500">{{ old('question_answer', $diary->question_answer) }}</textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('diary.index') }}" class="text-sm text-gray-500 hover:underline">キャンセル</a>
                <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-md transition duration-200">
                    更新する
                </button>
            </div>
        </form>
    </div>

</body>
</html>