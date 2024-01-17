<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'authors';

    protected $fillable = [
        'id',
        'name',
        'voters',
    ];

    function books() : HasMany {
        return $this->hasMany(book::class, 'author_id', 'id');
    }
}
