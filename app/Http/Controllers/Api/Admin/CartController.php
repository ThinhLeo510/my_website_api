<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Exception;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Count;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class CartController extends Controller
{
    //get all cart of user
    public function getCart($user_id){
        

        //check user_id is number, GET: put user_id to url
        if(is_numeric($user_id)){
            // $cart= DB::table('carts')->where('user_id',$user_id)->get();
            $cart=User::find($user_id)->cart;
            
            //check if cart item is empty   
            if(Count($cart)===0){
               
                return response()->json([
                    'code' => config('apiconst.DATA_EMPTY'),
                    'message'=> "Giỏ hàng chưa có sản phẩm"
                ]);
            }else{
                //get data cart 
                $product=null;
                $data= json_decode($cart);
                // dd($data);
                foreach($data as $item){
                    $product= Product::find($item->product_id);
                    // dd(1);
                    $item->product_infor = $product;
                }

                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'data' =>$data
                ]);
            }
        }else{
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message'=> "Page not found"
            ]);
        }
    }

    //add product to cart, POST: product_id , quantity of product
    public function addToCart(Request $request){
        
        try{
            $validator = Validator::make($request->all(), [
                'user_id' =>['required','numeric'],
                'product_id' => ['required', 'numeric'],
                'quantity' => ['required','numeric','integer','min:1'],
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'code' => config('apiconst.VALIDATE_ERROR'),
                    'error' => $validator->errors(),
                ]);
            }
            
            $cartExist= DB::table('carts')->where('user_id', $request->user_id)->where('product_id',$request->product_id)->get();
            // $cartExist= json_decode($cartExist);
            //check if product available in cart -> update quantity
            if(Count($cartExist)==0){
                $product= Product::find($request->product_id);
                
                $cart= Cart::create([
                    'user_id'=> $request->user_id,
                    'product_id' =>$request->product_id,
                    'product_price'=> $product->price,
                    'quantity' => $request->quantity,
                    'price_total' => $product->price * $request->quantity
                ]);
                
                if($cart){
                    return response()->json([
                        'code' => config('apiconst.API_OK'),
                        'message' => 'Add to cart successfully',
                        'data' => $cart,
                    ], 200);
                }else{
                    return response()->json([
                        'code'=>config('apiconst.SERVER_ERROR'),
                        'error' => 'Add to cart failed'
                    ], 400);
                }
            }else{
                $product= Product::find($request->product_id);
                
                $get_cart= Cart::select('quantity')
                            ->where('user_id',$request->user_id)
                            ->where('product_id',$request->product_id)
                            ->get();

                // get quantity cart before update
                $quantity_current= json_decode($get_cart)[0]->quantity;

                // get quantity cart after update
                $quantity_updated= $quantity_current + $request->quantity;

                // update cart 
                $cart= Cart::where('user_id',$request->user_id)
                            ->where('product_id',$request->product_id)
                            ->update([
                                'quantity'=> $quantity_updated,
                                'price_total'=> $product->price * $quantity_updated
                            ]);
                
                if($cart){
                    return response()->json([
                        'code' => config('apiconst.API_OK'),
                        'message' => 'Add to cart successfully',
                        
                    ], 200);
                }else{
                    return response()->json([
                        'code'=>config('apiconst.SERVER_ERROR'),
                        'error' => 'Add to cart failed'
                    ], 400);
                }
            }
            
        }catch(Exception $e){
            return response()->json([
                'code'=>config('apiconst.SERVER_ERROR'),
                'error' => $e
            ], 400);
        }
    }

    //update quantity product in cart
    public function cartUpdate(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'user_id' =>['required','numeric'],
                'product_id' => ['required', 'numeric'],
                'quantity' => ['required','numeric','integer','min:1'],
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'code' => config('apiconst.VALIDATE_ERROR'),
                    'error' => $validator->errors(),
                ]);
            }

            $product= Product::find($request->product_id);
            
            // update cart 
            $cart= Cart::where('user_id',$request->user_id)
                        ->where('product_id',$request->product_id)
                        ->update([
                            'quantity' => $request->quantity,
                            'price_total' => $product->price * $request->quantity

                        ]);
    
            if($cart){
                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'message' => 'Update cart successfully',
                    
                ], 200);
            }else{
                return response()->json([
                    'code'=>config('apiconst.SERVER_ERROR'),
                    'error' => 'Update cart failed'
                ], 400);
            }
        }catch(Exception $e){
            return response()->json([
                'code'=>config('apiconst.SERVER_ERROR'),
                'error' => $e
            ], 400);
        }
    }

    // remove 
    public function removeCart($cart_id){
        try{
            
            $cart= Cart::find($cart_id)->delete();
            
            if($cart){
                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'message' => 'Remove successfully',
                    
                ], 200);
            }else{
                return response()->json([
                    'code'=>config('apiconst.SERVER_ERROR'),
                    'error' => 'Remove failed'
                ], 400);
            }
        }catch(Exception $e){
            return response()->json([
                'code'=>config('apiconst.SERVER_ERROR'),
                'error' => $e
            ], 400);
        }
    }
}
