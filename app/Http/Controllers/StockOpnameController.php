<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use Illuminate\Http\Request;
use App\Models\ReturnItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class StockOpnameController extends Controller
{  

    public function index()
    {

    $opnames = StockOpname::with('product','user')
                ->latest()
                ->paginate(10);

    $returns = ReturnItem::with('product')
                ->whereIn('status',['pending','rejected'])
                ->latest()
                ->get();
    
    //$totalChecked = StockOpname::count(); // jika ambil dari database tapi belum buat ..
    return view('stock-opname', compact('opnames','returns')); // 'totalChecked' jika ambil dari db tambahkan ini

    }

    public function data()
    {

    $opnames = StockOpname::with('product','user')
                ->latest()
                ->paginate(10);

    return response()->json([

        'success' => true,
        'data' => $opnames

    ]);

    }

    public function store(Request $request)
    {
    $product = Product::findOrFail(
        $request->product_id
    );

    $system = $product->stock;
    $physical = $request->physical_stock;
    $difference = $physical - $system;

    $status = $difference == 0
        ? 'match'
        : 'discrepancy';

    $existing = StockOpname::where(
        'session_code',
        $request->session_code
    )
    ->where(
        'product_id',
        $product->id
    )
    ->first();

    if($existing){

        $existing->update([

            'system_stock' => $system,
            'physical_stock' => $physical,
            'difference' => $difference,
            'status' => $status,
            'session_status' => 'open',

        ]);

        $opname = $existing;

    }else{

        $opname = StockOpname::create([

        'product_id' => $product->id,
        'system_stock' => $system,
        'physical_stock' => $physical,
        'difference' => $difference,
        'status' => $status,

        'session_code' => $request->session_code,
        'session_status' => 'open',

        'created_by' => Auth::id()

        ]);

    }

    return response()->json([

        'success' => true,
        'data' => $opname

        ]);
    }

    public function history(Request $request)
    {
        $query = StockOpname::with(
            'product',
            'user'
        );

        if($request->session_code){

            $query->where(
                'session_code',
                $request->session_code
            );

        }

        $opnames = $query
            ->latest()
            ->get();

        return response()->json($opnames);
    }

    public function closeSession(Request $request)
    {
        StockOpname::where(
            'session_code',
            $request->session_code
        )
        ->update([

            'session_status' => 'closed'

        ]);

        return response()->json([

            'success' => true,
            'message' => 'Session closed'

        ]);
    }

}

