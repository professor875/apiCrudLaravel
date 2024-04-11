<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'products' => Product::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:15|min:2|string',
            'description'=>'required|max:55|min:5|string',
            'price'=>'required|max:10|min:3',
        ]);
        if ($validator->fails()){
            return response()->json([
                'status'=>422,
                'error'=>$validator->messages()
            ], 422);
        }else{
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
            ]);
            return response()->json([
                'status'=>200,
                'message'=>'Product has been created !!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'product' => Product::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:15|min:2|string',
            'description'=>'required|max:55|min:5|string',
            'price'=>'required|max:10|min:3|string',
        ]);
        if ($validator->fails()){
            return response()->json([
                'status'=>422,
                'error'=>$validator->messages()
            ], 422);
        }else{
            $product = Product::find($id);
            if ($product){
                $product->name = $request->input('name');
                $product->description = $request->input('description');
                $product->price = $request->input('price');
                $product->update();

                return response()->json([
                'status'=>200,
                'message'=>'Product has been Updated !!'
                ]);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => 'No product found'
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if ($product){
            $product->delete();
            return response()->json([
                'status'=> 200,
                'message' => 'Product has been deleted !!'
            ]);
        }else{
            return response()->json([
                'status'=> 500,
                'error'=> 'product not found'
            ], 500);
        }
    }
}
