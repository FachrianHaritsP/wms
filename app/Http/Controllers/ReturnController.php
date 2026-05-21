<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnItem;
use Illuminate\Support\Facades\Auth;

class   ReturnController extends Controller
{

public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'qty' => 'required|integer|min:1',
        'reason' => 'required'
    ]);

    $return = ReturnItem::create([
        'product_id' => $request->product_id,
        'qty' => $request->qty,
        'reason' => $request->reason,
        'status' => 'pending',
        'created_by' => Auth::id(),
        'notes' => $request->notes,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Return created',
        'data' => $return
    ]);
}


}
