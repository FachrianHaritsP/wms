<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\alert;

class StockController extends Controller
{
    //scan barang  masuk
    public function stockIn(Request $request)
    {
        $product = Product::find($request->product_id); //Product::where('sku',$request->sku)->first();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        if(!$product){
           /*  return response()->json([
                'message' => 'Product not found'
            ],404); */
            return response()->json([
            'success' => false,
            'message' => 'Product not found'
            ],404);
        }

        $product->stock += $request->qty;
        $product->save();

        StockTransaction::create([
            'product_id' => $product->id,
            'type' => 'in',
            'qty' => $request->qty,
            'created_by' => Auth::id()
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action'=>'stock_in',
            'description'=>'Menambah stok produk ' .$product->sku. ' sebanyak '. $request->qty,
        ]);

        return response()->json([
            'success' => true,
            'message'=>'Stock added',
            'data'=> $product
        ]);
    }//end stockin

    //scan barang keluar    
    public function stockOut(Request $request)
    {
        $product = Product::find($request->product_id); //Product::where('sku',$request->sku)->first()

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);


        if(!$product){

            return response()->json([
            'success' => false,
            'message' => 'Product not found'
            ],404);
        }

        if($product->stock < $request->qty){

            return response()->json([
            'success' => false,
            'message' => 'Stock not enough'
            ],400);
        }

        $product->stock -= $request->qty;
        $product->save();

        StockTransaction::create([
            'product_id' => $product->id,
            'type' => 'out',
            'qty' => $request->qty
        ]);

        ActivityLog::create([
            'user_id'=>Auth::id(),
            'action'=>'stock_out',
            'description'=>'Mengurangi stok produk ' .$product->sku. ' sebanyak ' .$request->qty,
        ]);

        return response()->json([
            'success'=> true,
            'message'=>'Stock reduced',
            'data'=> $product
        ]);
    }//end stockout

    function history()
    {
         $transactions = StockTransaction::with('product','user')
                    ->latest()
                    ->paginate(10);
                    //->get();

        return response()->json([
            'success'=>true,
            'message'=>'Transactions data fetched',
            'data'=>$transactions
        ]);

    }//end history inev

}//end stockcontroller
