<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\StockOpnameSession;
use App\Models\StockOpnameDetail;

class StockOpnameController extends Controller
{  

    //     StockOpnameController

    // 🟢 activeSession()  READY
    // 🔵 index()          WEB
    // 🟢 data()           READY
    // 🟢 store()          READY
    // 🟢 history()        READY
    // 🟢 closeSession()   READY

    public function startSession()
    {
        $active = StockOpnameSession::where('status', 'open')
            ->latest()
            ->first();

        if ($active) {
            return response()->json([
                'success' => false,
                'message' => 'Masih ada session yang aktif.',
                'data' => $active
            ], 400);
        }

        $session = StockOpnameSession::create([
            'session_code' => 'OPN-' . time(),
            'status' => 'open',
            'created_by' => Auth::id(),
            'started_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Session berhasil dibuat.',
            'data' => $session
        ]);
    }

    public function activeSession()
    {

        $session = StockOpnameSession::where('status', 'open')
        ->latest()
        ->first();

          if (!$session) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Tidak ada session aktif.'
            // ], 404);

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada session aktif.'
            ]);
        }


        return response()->json([
            'success' => true,
            'data' => $session
        ]);

    }


    public function index()
    {
        $returns = ReturnItem::with('product')
                    ->whereIn('status', ['pending', 'rejected'])
                    ->latest()
                    ->get();

        return view('stock-opname', compact('returns'));
    }

    public function store(Request $request)
    {
        $product = Product::findOrFail(
            $request->product_id
        );

        $session = StockOpnameSession::where('status', 'open')
            ->latest()
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada session stock opname yang aktif.'
            ], 400);
        }


        $system = $product->stock;
        $physical = $request->physical_stock;
        $difference = $physical - $system;

        $status = $difference == 0
            ? 'match'
            : 'discrepancy';

        $existing = StockOpnameDetail::where(
        'session_id',
        $session->id
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
            'match_status' => $status,
        ]);

        $opname = $existing;

    }else{

        $opname = StockOpnameDetail::create([

            'session_id' => $session->id,
            'product_id' => $product->id,
            'system_stock' => $system,
            'physical_stock' => $physical,
            'difference' => $difference,
            'match_status' => $status,

        ]);

    }

    return response()->json([

        'success' => true,
        'data' => $opname

        ]);
    }


    public function history(Request $request)
    {
        $query = StockOpnameDetail::with([
            'product',
            'session.user'
        ]);

        if ($request->session_code) {

            $query->whereHas('session', function ($q) use ($request) {

                $q->where(
                    'session_code',
                    $request->session_code
                );

            });

        }

        $details = $query
            ->latest()
            ->get();

        return response()->json([
            'session_code' => $request->session_code, //buat sementar
            'success' => true,
            'data' => $details
        ]);
    }

    public function closeSession(Request $request)
    {
 
        $session = StockOpnameSession::where('status', 'open')->first();

        if (!$session) {

            return response()->json([
                'success' => false,
                'message' => 'Session tidak ditemukan.'
            ], 404);

        }

        if ($session->status === 'closed') {

            return response()->json([
                'success' => false,
                'message' => 'Session sudah ditutup.'
            ], 400);

        }

        $session->update([
            'status' => 'closed',
            'closed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Session berhasil ditutup.'
        ]);
    }


}

