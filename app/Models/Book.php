<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'books';

    protected $fillable = [
        'id',
        'name',
        'book_category_id',
        'author_id',
        'average_rating',
        'voters',
    ];

    function category() : BelongsTo {
        return $this->belongsTo(BookCategory::class, 'book_category_id', 'id');
    }

    function author() : BelongsTo {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    function ratings() : HasMany {
        return $this->hasMany(Rating::class, 'book_id', 'id');
    }
}
