<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
   protected $fillable = [
    'sku',
    'name',
    'size',
    'color',
    'stock',
    'rack_slot_id',
    ];

    public function rackSlot()
    {
        return $this->belongsTo(RackSlot::class);
    }

}
