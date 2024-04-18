<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['invoice_number', 'title','type','description','image'];

    const BUY = 'buy';
    const SELL = 'sell';

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
}
