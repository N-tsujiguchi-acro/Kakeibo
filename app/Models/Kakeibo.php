<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kakeibo extends Model
{
    use HasFactory;
    use SoftDeletes;  // これを追加

    // テーブル名（省略可能：Laravelの規約に従えば不要）
    protected $table = 'kakeibos';

    // ホワイトリスト（入力許可するカラム）
    protected $fillable = [
        'title',
        'amount',
        'category_id',
        'comment',
        'date',
        'user_id',
    ];

    protected $dates = ['deleted_at'];  // これも追加（Laravel 8以下など）

    // app/Models/Kakeibo.php

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/Kakeibo.php

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
