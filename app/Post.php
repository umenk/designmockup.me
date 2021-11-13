<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['keyword', 'parent'];
    protected $casts = [
        'ingredients' => 'array',
        'templates' => 'array'
    ];

    protected $append = [
        'previous',
        'next',
        'parent'
    ];

    protected $dates = [
        'published_at'
    ];

    use HasFactory;

    public function getPreviousAttribute()
    {
        return Post::where('id', '<', $this->id)->orderBy('id', 'desc')->whereNotNull('content')->first();
    }

    public function getParentAttribute($value)
    {
        return Post::where('keyword', $value)->whereNotNull('content')->first();
    }

    public function getNextAttribute()
    {
        return Post::where('id', '>', $this->id)->orderBy('id', 'asc')->whereNotNull('content')->first();
    }
}
