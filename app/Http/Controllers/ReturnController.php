<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnItem;
use Illuminate\Support\Facades\Auth;

class   ReturnController extends Controller
{

public function index()
{
    $returns = ReturnItem::with('product','user')
        ->latest()
        ->paginate(10);
        //->get();

    return view('returns', compact('returns'));
}

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

public function review()
{
    $returns = ReturnItem::with('product','user')
        ->latest()
        ->paginate(10);

    return view('returns-review', compact('returns'));
}

public function approve($id)
{
    $return = ReturnItem::findOrFail($id);

    if($return->status != 'pending'){

    return back();

    }

    $return->status = 'approved';

    $return->save();

    $product = $return->product;
    $product->stock += $return->qty;
    $product->save();

    return back();

}

public function reject($id)
{
    $return = ReturnItem::findOrFail($id);
    if($return->status != 'pending'){

    return back();

    }
    $return->status = 'rejected';

    $return->save();
    return back();
}

}
