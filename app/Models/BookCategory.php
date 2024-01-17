<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'book_categories';

    protected $fillable = [
        'id',
        'name',
    ];
}
