<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use LocaleTraits;

    protected $fillable = ['title', 'content', 'user_id'];

    protected $casts = [
        'title' => 'json',
        'content' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTitleAttribute()
    {
        return $this->getLocale($this->attributes['title']);
    }

    public function getContentAttribute()
    {
        return $this->getLocale($this->attributes['content']);
    }
}
