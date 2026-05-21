<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {

       return response()->json([
        'success' => true,
        'message' => 'found',
        'kpi' => $this->getKpi(),
        'low_stock' => $this->getLowStock(),
        'product_movement' => $this->getProductMovement(),
        'stock_movement' => $this->getStockMovement()
        ]);

    }//end index

    private function getKPI()
    {
        return [
            'total_products' => Product::count(),
            'total_stock' => Product::sum('stock'),
            'stock_in' => StockTransaction::where('type','in')->sum('qty'),
            'stock_out' => StockTransaction::where('type','out')->sum('qty')
        ];
    }

    private function getLowStock()
    {
    return Product::where('stock','<=',5)->get();
    }

    private function getProductMovement()
    {
        return StockTransaction::select(
            //'product_id',
            'products.name',
            'products.sku',
            //DB::raw('SUM(qty) as total_out')
            DB::raw('SUM(stock_transactions.qty) as total_out')
        )
        ->join('products','products.id','=','stock_transactions.product_id')
        ->where('stock_transactions.type','out')
        ->groupBy('products.name','products.sku')
        ->orderByDesc('total_out')
        ->take(5)
        ->get();
    }

    private function getStockMovement()
    {
        return StockTransaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(CASE WHEN type="in" THEN qty ELSE 0 END) as total_in'),
            DB::raw('SUM(CASE WHEN type="out" THEN qty ELSE 0 END) as total_out')
        )
        ->groupBy('date')
        ->orderBy('date','desc')
        ->take(7)
        ->get();
    }


}
