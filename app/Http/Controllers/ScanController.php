<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use function Laravel\Prompts\alert;

class ScanController extends Controller
{

    public function scan($sku) #Request $request
    {
        #$product = Product::where('sku',$request->sku)->first(); work cuma belum komplit
        $product = Product::with('rackSlot.rack')
                ->where('sku', $sku) #$request->sku
                ->first();

        if(!$product){
            alert('== Produk tidak ditemukan ==');
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ],404);
            }

        return response()->json([
            'success' => true,
            'message'=>'Scan Success',  
            'data'=>$product
        ]);
    }


  public function generate($sku)
    {
        return QrCode::size(100)->generate($sku);
    }


}
