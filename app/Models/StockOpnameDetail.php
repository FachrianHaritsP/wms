<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected $fillable = [
        'session_id',
        'product_id',
        'system_stock',
        'physical_stock',
        'difference',
        'match_status',
    ];

    public function session()
    {
        return $this->belongsTo(StockOpnameSession::class, 'session_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
