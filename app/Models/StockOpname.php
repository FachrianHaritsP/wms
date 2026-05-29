<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = [

        'product_id',
        'system_stock',
        'physical_stock',
        'difference',
        'status',
        'created_by'

    ];

     // relation product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // relation user
    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }


}
