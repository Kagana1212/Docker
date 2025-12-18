<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    // フォームから保存しても良い項目を指定する（セキュリティ対策）
    protected $fillable = ['title', 'content', 'emotion', 'question_answer'];
}
