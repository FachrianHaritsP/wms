<?php
//API ready
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class ScanController extends Controller
{

    public function scan($sku) #Request $request
    {

         if(empty($sku)){

            return response()->json([
                'success' => false,
                'message' => 'SKU is required'
            ],400);

        }

        #$product = Product::where('sku',$request->sku)->first(); work cuma belum komplit
        $product = Product::with('rackSlot.rack')
                ->where('sku', $sku) #$request->sku
                ->first();

        if(!$product){

                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ],404);
            }

        return response()->json([
            'success' => true,
            'message'=>'Scan Success',  
            'data'=> $product
        ]);
    }

    
    //web only
  public function generate($sku)
    {
        return QrCode::size(100)->generate($sku);
    }


}
