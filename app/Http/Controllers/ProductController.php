<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RackSlot;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if($request->search){
            $query->where(function($q) use ($request){
            $q->where('sku','like','%'.$request->search.'%')
            ->orWhere('name','like','%'.$request->search.'%');
            });
        }   

        if($request->size){
            $query->where('size', $request->size);
        }

        if($request->color){
            $query->where('color', $request->color);
        }
    
        //$product = $query->get(); work bawaan
        $products = $query->with('rackSlot.rack')->paginate(10); // ->get();
        //$products = Product::with('rackSlot.rack')->get(); bug search tidak jalan klo pake ini


        //return response()->json($products);//$query->get()
        return response()->json([
            'success' => true,
            'message' => 'Product fetched',
            'data' => $products
        ]);
    }//end


    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:products,sku,',
            'name' => 'required',
            'size' => 'required',
            'color' => 'required',
            'stock' => 'required|integer|min:0',
            'rack_slot_id' => 'nullable|exists:rack_slots,id'
        ]);

        $product = Product::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'size' => $request->size,
            'color' => $request->color,
            'stock' => $request->stock,
            'rack_slot_id' => $request->rack_slot_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created',
            'data' => $product
        ]);
    }

    public function show($id)
    {
        //$product = Product::find($id);
        $product = Product::with('rackSlot.rack')
            ->find($id);

        if(!$product){
            /* return response()->json([
                'message' => 'Product not found'
            ], 404); */
            return response()->json([
            'success' => false,
            'message' => 'Product not found'
            ],404);
        }

        //return response()->json($product);
        return response()->json([
            'success' => true,
            'message' => 'Product fetched',
            'data' => $product
        ]);
    }//end

    public function update(Request $request, $id)
    {
        //$product = Product::find($id);
        $product = Product::with('rackSlot.rack')
            ->find($id);

        $request->validate([
            'sku' => 'required|unique:products,sku,' . $id,
            'name' => 'required',
            'size' => 'required',
            'color' => 'required',
            'stock' => 'required|integer|min:0'
        ]);

        if(!$product){
            /* return response()->json([
                'message' => 'Product not found'
            ], 404); */
            return response()->json([
            'success' => false,
            'message' => 'Product not found'
            ],404);
        }

        $product->update([
            'sku' => $request->sku,
            'name' => $request->name,
            'size' => $request->size,
            'color' => $request->color,
            'stock' => $request->stock,
            'rack_slot_id' => $request->rack_slot_id,
        ]);

       /*  return response()->json([
            'message' => 'Product updated',
            'data' => $product
        ]); */
        return response()->json([
        'success' => true,
        'message' => 'Product updated',
        'data' => $product
        ]);


    }//end

    public function destroy($id)
    {
        $product = Product::find($id);

        if(!$product){
            /* return response()->json([
                'message' => 'Product not found'
            ], 404); */
            return response()->json([
            'success' => false,
            'message' => 'Product not found'
            ],404);
        }

        $product->delete();

       /*  return response()->json([
            'message' => 'Product deleted'
        ]); */
        return response()->json([
        'success' => true,
        'message' => 'Delete success',
        'data' => $product
        ]);

    }//end

}