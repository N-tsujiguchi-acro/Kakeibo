<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',        // ← これを追加
        'category_id',
        'month',
        'amount',
    ];
    // app/Models/Budget.php
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
