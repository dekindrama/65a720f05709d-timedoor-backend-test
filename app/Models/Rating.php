<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'ratings';

    protected $fillable = [
        'id',
        'book_id',
        'scale',
    ];
}
