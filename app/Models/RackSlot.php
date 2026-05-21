<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RackSlot extends Model
{
    //
    protected $fillable=[
        'rack_id',
        'slot_code'
    ];

    public function products()
    {
     return $this->hasMany(Product::class);
    }

    public function rack()
    {
     return $this->belongsTo(Rack::class);
    }   


}
