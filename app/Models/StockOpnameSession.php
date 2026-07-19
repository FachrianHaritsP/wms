<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameSession extends Model
{
     protected $fillable = [
        'session_code',
        'status',
        'created_by',
        'started_at',
        'closed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(StockOpnameDetail::class, 'session_id');
    }
}
