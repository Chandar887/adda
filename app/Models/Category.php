<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =  [
        'id',
        'name',
        'parent',
        'level',

    ];

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent');
    }
}
