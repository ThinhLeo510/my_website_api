<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:products'],
            'category_id' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'discount_price' => ['numeric'],
            'thumbnail'=> ['required','string'],
            'discount_id' => ['numeric']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $product=Product::create([
            
        ]);
    }
}
