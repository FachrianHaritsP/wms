<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\StockOpname;

class ReportController extends Controller
{
    public function index()
    {

        $transactions = StockTransaction::with(
            'product',
            'user'
        )

        ->latest()

        ->paginate(10);

        //kpi    
        $totalStockIn = StockTransaction::where(
            'type',
            'in'
        )->sum('qty');

        $totalStockOut = StockTransaction::where(
            'type',
            'out'
        )->sum('qty');

        $totalTransactions = StockTransaction::count();


        //query top movement
        $topMovements = StockTransaction::select(

                'product_id',

                DB::raw("
                    SUM(
                        CASE
                            WHEN type = 'in'
                            THEN qty
                            ELSE 0
                        END
                    ) as total_in
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN type = 'out'
                            THEN qty
                            ELSE 0
                        END
                    ) as total_out
                ")

        )
        ->with('product')
        ->groupBy('product_id')
        //->orderByRaw('total_in + total_out DESC')
        ->orderByRaw('
                        SUM(
                            CASE
                                WHEN type = "in"
                                THEN qty
                                ELSE 0
                            END
                        )

                        +

                        SUM(
                            CASE
                                WHEN type = "out"
                                THEN qty
                                ELSE 0
                            END
                        )

                        DESC
                    ')
        ->take(3)
        ->get();
         
        // low stock
        $lowStocks = Product::where(
            'stock',
            '<=',
            5
        )

        ->orderBy('stock','asc')

        ->take(5)

        ->get();

        //return-report
        $returnApproved = ReturnItem::where(
            'status',
            'approved'
        )->count();

        $returnPending = ReturnItem::where(
            'status',
            'pending'
        )->count();

        $returnRejected = ReturnItem::where(
            'status',
            'rejected'
        )->count();

        //stockopname-report
        $opnameSummary = StockOpname::select(

                'session_code',

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'match'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_match
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'discrepancy'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_discrepancy
                ")

        )

        ->whereNotNull('session_code')

        ->groupBy('session_code')

        ->latest()

        ->take(5)

        ->get();
        //dd($opnameSummary->toArray());


        return view('reports.index', [

            'transactions' => $transactions,
            'totalStockIn' => $totalStockIn,
            'totalStockOut' => $totalStockOut,
            'topMovements' => $topMovements,
            'lowStocks' => $lowStocks,
            //return-report
            'returnApproved' => $returnApproved,
            'returnPending' => $returnPending,
            'returnRejected' => $returnRejected,
            //stockopname-report
            'opnameSummary' => $opnameSummary,

            'totalTransactions' => $totalTransactions

        ]);

    }
}
