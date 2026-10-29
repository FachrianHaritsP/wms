<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    //
   protected $fillable = [
    'sku',
    'name',
    'size',
    'color',
    'stock',
    'price',
    'rack_slot_id',
    ];

    public function rackSlot()
    {
        return $this->belongsTo(RackSlot::class);
    }

    public function stockOpnameDetails()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }

    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }
    
}
