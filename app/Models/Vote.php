<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_source',
        'news_keyword',
        'published_at'
    ];

    protected $attributes = [
        'count' => 1
    ];

    protected function newsSource(): Attribute 
    {
        return Attribute::make(
            set: fn ($value) =>  Str::snake(strtolower($value), '-')
        );
    }

    // protected function count(): Attribute 
    // {
    //     return Attribute::make(
    //         set: fn ($value) => ($this->upVote === false AND $value <= 2) 
    //             ? $value-- 
    //             : $value++
    //     );
    // }

}
