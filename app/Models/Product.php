<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'quantity', 'price', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCategoryTitleAttribute()
    {
        return $this->category->title ?? '';
    }

    public function invoice()
    {
        return $this->belongsToMany(Invoice::class)->withPivot('quantity');
    }
}
