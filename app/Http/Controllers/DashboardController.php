<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->period ?? 'today';

        return response()->json([

            'success' => true,
            'message' => 'found',

            'kpi' => $this->getKPI($period),

            'low_stock' => $this->getLowStock(),

            'product_movement' => $this->getProductMovement($period),

            'stock_movement' => $this->getStockMovement($period)

        ]);
    }

    private function getKPI($period)
    {
        $stockIn = StockTransaction::where(
            'type',
            'in'
        );

        $stockOut = StockTransaction::where(
            'type',
            'out'
        );

        if($period == 'today'){

            $stockIn->whereDate(
                'created_at',
                today()
            );

            $stockOut->whereDate(
                'created_at',
                today()
            );

        }elseif($period == 'week'){

            $stockIn->whereBetween(
                'created_at',
                [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]
            );

            $stockOut->whereBetween(
                'created_at',
                [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]
            );

        }elseif($period == 'month'){

            $stockIn->whereMonth(
                'created_at',
                now()->month
            );

            $stockOut->whereMonth(
                'created_at',
                now()->month
            );

        }

        return [

            'total_products' => Product::count(),

            'total_stock' => Product::sum('stock'),

            'stock_in' => $stockIn->sum('qty'),

            'stock_out' => $stockOut->sum('qty')

        ];
    }

    private function getLowStock()
    {
    return Product::where('stock','<=',5)->get();
    }

    // private function getProductMovement($period )
    // {
    //     return StockTransaction::select(
    //         //'product_id',
    //         'products.name',
    //         'products.sku',
    //         //DB::raw('SUM(qty) as total_out')
    //         DB::raw('SUM(stock_transactions.qty) as total_out')
    //     )
    //     ->join('products','products.id','=','stock_transactions.product_id')
    //     ->where('stock_transactions.type','out')
    //     ->groupBy('products.name','products.sku')
    //     ->orderByDesc('total_out')
    //     ->take(5)
    //     ->get();
    // }

    private function getProductMovement($period)
    {
        $query = StockTransaction::select(

            'products.name',
            'products.sku',

            DB::raw(
                'SUM(stock_transactions.qty) as total_out'
            )

        )
        ->join(
            'products',
            'products.id',
            '=',
            'stock_transactions.product_id'
        )
        ->where(
            'stock_transactions.type',
            'out'
        );

        if($period == 'today'){

            $query->whereDate(
                'stock_transactions.created_at',
                today()
            );

        }elseif($period == 'week'){

            $query->whereBetween(
                'stock_transactions.created_at',
                [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]
            );

        }elseif($period == 'month'){

            $query->whereMonth(
                'stock_transactions.created_at',
                now()->month
            );

        }

        return $query
            ->groupBy(
                'products.name',
                'products.sku'
            )
            ->orderByDesc(
                'total_out'
            )
            ->take(5)
            ->get();
    }

    // private function getStockMovement()
    // {
    //     return StockTransaction::select(
    //         DB::raw('DATE(created_at) as date'),
    //         DB::raw('SUM(CASE WHEN type="in" THEN qty ELSE 0 END) as total_in'),
    //         DB::raw('SUM(CASE WHEN type="out" THEN qty ELSE 0 END) as total_out')
    //     )
    //     ->groupBy('date')
    //     ->orderBy('date','desc')
    //     ->take(7)
    //     ->get();
    // }

    private function getStockMovement($period)
    {
        $query = StockTransaction::select(

            DB::raw('DATE(created_at) as date'),

            DB::raw(
                'SUM(CASE WHEN type="in" THEN qty ELSE 0 END) as total_in'
            ),

            DB::raw(
                'SUM(CASE WHEN type="out" THEN qty ELSE 0 END) as total_out'
            )

        );

        if($period == 'today'){

            $query->whereDate(
                'created_at',
                today()
            );

        }elseif($period == 'week'){

            $query->whereBetween(
                'created_at',
                [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]
            );

        }elseif($period == 'month'){

            $query->whereMonth(
                'created_at',
                now()->month
            );

        }

        return $query
            ->groupBy('date')
            ->orderBy('date','desc')
            ->take(7)
            ->get();
    }

    private function getDateFilter($query, $period)
    {
        if($period == 'today'){

            $query->whereDate(
                'created_at',
                today()
            );

        }elseif($period == 'week'){

            $query->whereBetween(
                'created_at',
                [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]
            );

        }elseif($period == 'month'){

            $query->whereMonth(
                'created_at',
                now()->month
            );
        }

        return $query;
    }


}
