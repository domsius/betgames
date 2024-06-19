<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'category_id',
        'priority',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
