<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Post extends Model
{
    use HasFactory, AsSource;
    protected $fillable = [
        'content',
        'title',
        'user_id'
    ];
    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function comments () : HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
