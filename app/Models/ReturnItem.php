<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    //
    protected $table = 'returns';

    protected $fillable = [
    'product_id',
    'qty',
    'reason',
    'status',
    'created_by',
    'notes',
    ];
}
