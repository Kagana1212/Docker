<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    // フォームから保存しても良い項目を指定する（セキュリティ対策）
   protected $fillable = [
    'user_id',
    'title', 
    'content', 
    'emotion', 
    'question_answer'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getEmotionStyleAttribute()
    {
        return match($this->emotion) {
            '喜' => 'bg-green-100 text-green-800',
            '怒' => 'bg-red-100 text-red-800',
            '悲' => 'bg-blue-100 text-blue-800',
            '楽' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

}
