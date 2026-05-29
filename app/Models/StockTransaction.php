<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    //

protected $fillable = [
    'product_id',
    'type',
    'qty',
    'created_by'
];

public function product()
{
    return $this->belongsTo(Product::class);
}//end product

//buat tau siapa yang melakukan
public function user()
{
    return $this->belongsTo(User::class,'created_by');
}

}
