<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use GuzzleHttp\RetryMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    // show one product
    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json([
                'data' => $product
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found'
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:products'],
            'category_id' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'discount_price' => ['numeric', 'nullable'],
            'thumbnail' => ['required', 'string'],
            'discount_id' => ['numeric', 'nullable']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'discount_price' => $request->discount_price,
            'thumbnail' => $request->thumbnail,
            'discount_id' => $request->discount_id
        ]);

        if ($product) {
            return response()->json([
                'message' => 'Created successfully',
                'data' => $product,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Create failed'
            ], 400);
        }
    }

    // update product
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['string', 'max:255'],
            'category_id' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'discount_price' => ['numeric', 'nullable'],
            'thumbnail' => ['required', 'string'],
            'discount_id' => ['numeric', 'nullable']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::find($id);
        if ($product) {

            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->discount_price = $request->discount_price;
            $product->thumbnail = $request->thumbnail;
            $product->discount_id = $request->discount_id;
            $product->save();

            return response()->json([
                'message' => 'Updated successfully',
                'data' =>$product
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found'
            ]);
        }
    }

    public function destroy($id){

        $product=Product::find($id);
        if($product){
            $product->delete();
            return response()->json([
                'message'=>'Deleted successfully'
            ], 200);
        }else{
            return response()->json([
                'message'=>'Data not found'
            ]);
        }

    }

    public function restore($id){
        $product =Product::onlyTrashed()->find($id);
        if($product){
            $product->restore();
            return response()->json([
                'message'=>'Restored successfully'
            ],200);
        }else{
            return response()->json([
                'message'=>'Data not found'
            ]);
        }
    }

    public function getListProductDeleted(){
        $product=Product::onlyTrashed()->get();
        if($product){
            return response()->json([
                'data'=>$product
            ],200);
        }else{
            return response()->json([
                'data'=>'nothing to show'
            ]);
        }
    }

    
}
