<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class   ReturnController extends Controller
{
    //returns
    // 🟢 store()     API Ready
    // 🟢 update()    API Ready
    // 🟢 cancel()    API Ready

    // 🔵 index()     Web Only
    // 🔵 review()    Web Only
    // 🔵 approve()   Web Only
    // 🔵 reject()    Web Only

    public function index()
    {
        $returns = ReturnItem::with(
            'product',
            'user'
        )
        ->where(
            'status',
            'pending'
        )
        ->latest()
        ->paginate(10);

        $products = Product::all();

        return view(
            'returns',
            compact(
                'returns',
                'products'
            )
        );
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

    // blade return-review
    public function review()
    {
        // $returns = ReturnItem::with('product','user')
        //     ->latest()
        //     ->paginate(10);

        // return view('returns-review', compact('returns'));

        $pendingReturns = ReturnItem::with(
            'product',
            'user'
        )

        ->where(
            'status',
            'pending'
        )

        ->latest()

        ->get();

        $historyReturns = ReturnItem::with(
        'product',
        'user'
            )

        ->whereIn(
            'status',
            [
                'approved',
                'rejected',
                'cancelled'
            ]
        )

        ->latest()

        ->paginate(10);

        return view(
        'returns-review',
        compact(
        'pendingReturns',
        'historyReturns'
        )
        );
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

    public function update(Request $request,$id)
    {
        $return = ReturnItem::findOrFail(
            $id
        );

        if(
            $return->status != 'pending'
        ){

            return response()->json([

                'success' => false,
                'message' =>
                'Return tidak dapat diubah'

            ],400);

        }

        $return->update([

            'product_id' =>
            $request->product_id,

            'qty' =>
            $request->qty,

            'reason' =>
            $request->reason,

            'notes' =>
            $request->notes

        ]);

        return response()->json([

            'success' => true,

            'message' =>
            'Return berhasil diperbarui'

        ]);
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

    public function cancel($id)
    {
        $return = ReturnItem::findOrFail($id);

        if($return->status != 'pending'){

            return response()->json([

                'success' => false,
                'message' => 'Return tidak dapat dibatalkan'

            ], 400);

        }

        $return->status = 'cancelled';

        $return->save();

        return response()->json([

            'success' => true,
            'message' => 'Return berhasil dibatalkan'

        ]);
    }


}
