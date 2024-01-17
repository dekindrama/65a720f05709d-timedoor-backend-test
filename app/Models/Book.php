<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'rating',
    ];
}
