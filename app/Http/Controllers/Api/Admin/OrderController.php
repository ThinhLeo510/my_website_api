<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    // get list order processing
    public function listOrder($page){
        $count = DB::table('orders')->where('status','=',1)->count();
        // dd($page);
        if(!isset($page)){
            $page=1;
        }

        //item tren moi trang
        $itemPerPage=20;
        //lam tron len so trang toi dang da chia
        $maxPage=ceil($count /$itemPerPage);
        ///kiem tra param la so , 0< page <= maxPage
        if(is_numeric($page) && $page>0 && $page<=$maxPage){
            $offset= ($page-1)*$itemPerPage;
            $data= DB::table('orders')->where('status','=',1)->offset($offset)->limit($itemPerPage)->get();
           
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'order' => $data,
                'perPage'=> $itemPerPage,
                'pageCurrent'=> $page,
                'maxPage' => $maxPage,
                'count'=>$count,
            ]);
        }

        return response()->json([
            'code' => config('apiconst.SERVER_ERROR'),
            'message'=>"Page not found",
            // 'count'=>$count,
            
        ]);
    }

    // get list order confirmed
    public function listOrderDone($page){
        $count = DB::table('orders')->where('status','=',2)->count();
        // dd($page);
        if(!isset($page)){
            $page=1;
        }

        if($count==0){
            return response()->json([
                'code' => config('apiconst.DATA_EMPTY'),
                'message'=> "Danh sách đơn đã xác nhận rỗng"
            ]);
        }

        //item tren moi trang
        $itemPerPage=20;
        //lam tron len so trang toi dang da chia
        $maxPage=ceil($count /$itemPerPage);
        // dd($maxPage);
        ///kiem tra param la so , 0< page <= maxPage
        if(is_numeric($page) && $page>0 && $page<=$maxPage){
            $offset= ($page-1)*$itemPerPage;
            $data= DB::table('orders')->where('status','=',2)->offset($offset)->limit($itemPerPage)->get();
           
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'order' => $data,
                'perPage'=> $itemPerPage,
                'pageCurrent'=> $page,
                'maxPage' => $maxPage,
                'count'=>$count,
            ]);
        }

        return response()->json([
            'code' => config('apiconst.SERVER_ERROR'),
            'message'=>"Page not found",
            // 'count'=>$count,
            
        ]);
    }

    // get list order destroy
    public function listOrderDestroy($page){
        try{

            $count = DB::table('orders')->where('status','=',3)->count();
            // dd($page);
            if(!isset($page)){
                $page=1;
            }

            if($count==0){
                return response()->json([
                    'code' => config('apiconst.DATA_EMPTY'),
                    'message'=> "Danh sách đơn huỷ rỗng"
                ]);
            }
    
            //item tren moi trang
            $itemPerPage=20;
            //lam tron len so trang toi dang da chia
            $maxPage=ceil($count /$itemPerPage);
            ///kiem tra param la so , 0< page <= maxPage
            if(is_numeric($page) && $page>0 && $page<=$maxPage){
                $offset= ($page-1)*$itemPerPage;
                $data= DB::table('orders')->where('status','=',3)->offset($offset)->limit($itemPerPage)->get();
               
                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'order' => $data,
                    'perPage'=> $itemPerPage,
                    'pageCurrent'=> $page,
                    'maxPage' => $maxPage,
                    'count'=>$count,
                ]);
            }
    
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message'=>"Page not found",
                // 'count'=>$count,
                
            ]);
        }catch( Exception $e){
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message'=>"Error: ".$e,
                
            ]);
        }
    }

    // get list order complete
    public function listOrderComplete($page){
        $count = DB::table('orders')->where('status','=',5)->count();
        // dd($page);
        if(!isset($page)){
            $page=1;
        }

        if($count==0){
            return response()->json([
                'code' => config('apiconst.DATA_EMPTY'),
                'message'=> "Danh sách đơn đã hoàn thành rỗng"
            ]);
        }

        //item tren moi trang
        $itemPerPage=20;
        //lam tron len so trang toi dang da chia
        $maxPage=ceil($count /$itemPerPage);
        ///kiem tra param la so , 0< page <= maxPage
        if(is_numeric($page) && $page>0 && $page<=$maxPage){
            $offset= ($page-1)*$itemPerPage;
            $data= DB::table('orders')->where('status','=',5)->offset($offset)->limit($itemPerPage)->get();
           
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'message'=> "get list order complete successfully",
                'order' => $data,
                'perPage'=> $itemPerPage,
                'pageCurrent'=> $page,
                'maxPage' => $maxPage,
                'count'=>$count,
            ]);
        }

        return response()->json([
            'code' => config('apiconst.SERVER_ERROR'),
            'message'=>"Page not found",
            
        ]);
    }

    // get list order shipping
    public function listOrdershipping($page){
        $count = DB::table('orders')->where('status','=',4)->count();
        // dd($page);
        if(!isset($page)){
            $page=1;
        }

        if($count==0){
            return response()->json([
                'code' => config('apiconst.DATA_EMPTY'),
                'message'=> "Danh sách đơn dang giao rỗng"
            ]);
        }

        //item tren moi trang
        $itemPerPage=20;
        //lam tron len so trang toi dang da chia
        $maxPage=ceil($count /$itemPerPage);
        ///kiem tra param la so , 0< page <= maxPage
        if(is_numeric($page) && $page>0 && $page<=$maxPage){
            $offset= ($page-1)*$itemPerPage;
            $data= DB::table('orders')->where('status','=',4)->offset($offset)->limit($itemPerPage)->get();
           
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'message'=> "get list order shipping successfully",
                'order' => $data,
                'perPage'=> $itemPerPage,
                'pageCurrent'=> $page,
                'maxPage' => $maxPage,
                'count'=>$count,
            ]);
        }

        return response()->json([
            'code' => config('apiconst.SERVER_ERROR'),
            'message'=>"Page not found",
            
        ]);
    }

    // creare order
    public function createOrder(Request $request){
        // validate
        // dd($request->all());
        try{
            $user = User::find($request->user_id);
            
            if($request->total >=20000){
                $shipping_fee=0;
            }else{
                $shipping_fee= 30000;
            }

            // dd($shipping_fee);

            $price_total = $request->total + $shipping_fee;
            // dd($price_total);



            $order = Order::create([
                'user_id' => $request->user_id,
                'firstname'=> $user->firstname,
                'lastname'=>$user->lastname,
                'phone' =>$user->phone,
                'address'=>$user->address,
                'notes' => $request->notes,
                'payment_id' => $request->payment_id,
                'status' => 1,
                'shipping_fee' => $shipping_fee,
                'voucher_id' => $request->voucher_id,
                'sub_price_total' => $request->total,
                'price_total' =>$price_total,
            ]);

            

            // $cart= Cart::find($user->user_id);
            // $cart->delete();

            if ($order) {
                // dd(json_decode($order));
                $carts = $user->cart;
                foreach($carts as $cart){
                    // dd( $cart->product_infor->price);
                    $order_detail = Order_detail::create([
                        'order_id' => $order->id,
                        'product_id'=> $cart->product_id,
                        'product_price'=>  $cart->product_price,
                        'quantity' => $cart->quantity,
                        'price_total'=> $cart->price_total,
                    ]);

                    //update quantity of product
                    

                    $product= Product::find($cart->product_id);
                    $product->quantity = $product->quantity - $cart->quantity;
                    $product->save();
                        
                    
                    
                    $cart_temp=Cart::find($cart->id);
                    $cart_temp->delete();
                }

                // dd($cart);
                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'message' => 'create order successfully',
                    'data' => $order,
                ], 200);
            } else {
                return response()->json([
                    'code'=>config('apiconst.SERVER_ERROR'),
                    'error' => 'create order failed'
                ], 400);
            }
        }catch(Exception $e){
            dd($e);
        }

    }

    // destroy order
    public function destroyOrder($id){
        if(is_numeric($id)){
            $order= Order::find($id);
            if($order){

                //update quantity of product
                $details= $order->orders_detail;
                foreach($details as $detail){

                    $product= Product::find($detail->product_id);
                    $product->quantity = $product->quantity + $detail->quantity;
                    $product->save();
                    
                }

                $order->status = 3;
                $order->save();
                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'message' => 'Update order status to destroy successfully'
                ]);
            }else{
                return response()->json([
                    'code' => config('apiconst.INVALIED'),
                    'message' => 'data not found'
                ]);
            }
        }else{
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message' => 'page not found'
            ]);
        }
    }

    //order completed
    public function confirmOrder($id){

        if(is_numeric($id)){
            $order= Order::find($id);
            if($order){
                $order->status = 2;
                $order->save();

                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'message' => 'Update order status to confirm successfully'
                ]);
            }else{
                return response()->json([
                    'code' => config('apiconst.INVALIED'),
                    'message' => 'data not found'
                ]);
            }
        }else{
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message' => 'page not found'
            ]);
        }
    }

    // don hang đang giao
    public function shippingOrder($id){

        if(is_numeric($id)){
            $order= Order::find($id);
            if($order){
                $order->status = 4;
                $order->save();

                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'message' => 'Update order status to shipping successfully'
                ]);
            }else{
                return response()->json([
                    'code' => config('apiconst.INVALIED'),
                    'message' => 'data not found'
                ]);
            }
        }else{
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message' => 'page not found'
            ]);
        }
    }

    // don hang da hoan thanh
    public function completeOrder($id){

        if(is_numeric($id)){
            $order= Order::find($id);
            if($order){
                $order->status = 5;
                $order->save();

                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'message' => 'Update order status to complete successfully'
                ]);
            }else{
                return response()->json([
                    'code' => config('apiconst.INVALIED'),
                    'message' => 'data not found'
                ]);
            }
        }else{
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message' => 'page not found'
            ]);
        }
    }

    // =================================USER========================================
    // get list order processing
    public function listOrderUser($page,$id){

        $count = DB::table('orders')->where('status','=',1)->where('user_id','=',$id)->count();
        // dd($page);
        // dd($count);
        if(!isset($page)){
            $page=1;
        }

        if($count==0){
            return response()->json([
                'code' => config('apiconst.DATA_EMPTY'),
                'message'=> "Danh sách đơn đang chờ xác nhận rỗng"
            ]);
        }

        //item tren moi trang
        $itemPerPage=20;
        //lam tron len so trang toi dang da chia
        $maxPage=ceil($count /$itemPerPage);
        ///kiem tra param la so , 0< page <= maxPage
        if(is_numeric($page) && $page>0 && $page<=$maxPage){
            $offset= ($page-1)*$itemPerPage;
            $data= DB::table('orders')->where('status','=',1)->where('user_id','=',$id)->offset($offset)->limit($itemPerPage)->get();
           
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'order' => $data,
                'perPage'=> $itemPerPage,
                'pageCurrent'=> $page,
                'maxPage' => $maxPage,
                'count'=>$count,
            ]);
        }

        return response()->json([
            'code' => config('apiconst.SERVER_ERROR'),
            'message'=>"Page not found",
            // 'count'=>$count,
            
        ]);
    }

    // get list order confirmed
    public function listOrderDoneUser($page,$id){
        $count = DB::table('orders')->where('status','=',2)->where('user_id','=',$id)->count();
        // dd($page);
        if(!isset($page)){
            $page=1;
        }

        if($count==0){
            return response()->json([
                'code' => config('apiconst.DATA_EMPTY'),
                'message'=> "Danh sách đơn đã xác nhận rỗng"
            ]);
        }

        //item tren moi trang
        $itemPerPage=20;
        //lam tron len so trang toi dang da chia
        $maxPage=ceil($count /$itemPerPage);
        // dd($maxPage);
        ///kiem tra param la so , 0< page <= maxPage
        if(is_numeric($page) && $page>0 && $page<=$maxPage){
            $offset= ($page-1)*$itemPerPage;
            $data= DB::table('orders')->where('status','=',2)->where('user_id','=',$id)->offset($offset)->limit($itemPerPage)->get();
           
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'order' => $data,
                'perPage'=> $itemPerPage,
                'pageCurrent'=> $page,
                'maxPage' => $maxPage,
                'count'=>$count,
            ]);
        }

        return response()->json([
            'code' => config('apiconst.SERVER_ERROR'),
            'message'=>"Page not found",
            // 'count'=>$count,
            
        ]);
    }

    // get list order destroy
    public function listOrderDestroyUser($page,$id){
        try{

            $count = DB::table('orders')->where('status','=',3)->where('user_id','=',$id)->count();
            // dd($page);
            if(!isset($page)){
                $page=1;
            }

            if($count==0){
                return response()->json([
                    'code' => config('apiconst.DATA_EMPTY'),
                    'message'=> "Danh sách đơn huỷ rỗng"
                ]);
            }
    
            //item tren moi trang
            $itemPerPage=20;
            //lam tron len so trang toi dang da chia
            $maxPage=ceil($count /$itemPerPage);
            ///kiem tra param la so , 0< page <= maxPage
            if(is_numeric($page) && $page>0 && $page<=$maxPage){
                $offset= ($page-1)*$itemPerPage;
                $data= DB::table('orders')->where('status','=',3)->where('user_id','=',$id)->offset($offset)->limit($itemPerPage)->get();
               
                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'order' => $data,
                    'perPage'=> $itemPerPage,
                    'pageCurrent'=> $page,
                    'maxPage' => $maxPage,
                    'count'=>$count,
                ]);
            }
    
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message'=>"Page not found",
                // 'count'=>$count,
                
            ]);
        }catch( Exception $e){
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message'=>"Error: ".$e,
                
            ]);
        }
    }

    // get list order complete
    public function listOrderCompleteUser($page,$id){
        $count = DB::table('orders')->where('status','=',5)->where('user_id','=',$id)->count();
        // dd($page);
        if(!isset($page)){
            $page=1;
        }

        if($count==0){
            return response()->json([
                'code' => config('apiconst.DATA_EMPTY'),
                'message'=> "Danh sách đơn đã hoàn thành rỗng"
            ]);
        }

        //item tren moi trang
        $itemPerPage=20;
        //lam tron len so trang toi dang da chia
        $maxPage=ceil($count /$itemPerPage);
        ///kiem tra param la so , 0< page <= maxPage
        if(is_numeric($page) && $page>0 && $page<=$maxPage){
            $offset= ($page-1)*$itemPerPage;
            $data= DB::table('orders')->where('status','=',5)->where('user_id','=',$id)->offset($offset)->limit($itemPerPage)->get();
           
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'message'=> "get list order complete successfully",
                'order' => $data,
                'perPage'=> $itemPerPage,
                'pageCurrent'=> $page,
                'maxPage' => $maxPage,
                'count'=>$count,
            ]);
        }

        return response()->json([
            'code' => config('apiconst.SERVER_ERROR'),
            'message'=>"Page not found",
            
        ]);
    }

    // get list order shipping
    public function listOrdershippingUser($page,$id){
        $count = DB::table('orders')->where('status','=',4)->where('user_id','=',$id)->count();
        // dd($page);
        if(!isset($page)){
            $page=1;
        }

        if($count==0){
            return response()->json([
                'code' => config('apiconst.DATA_EMPTY'),
                'message'=> "Danh sách đơn đang giao rỗng"
            ]);
        }

        //item tren moi trang
        $itemPerPage=20;
        //lam tron len so trang toi dang da chia
        $maxPage=ceil($count /$itemPerPage);
        ///kiem tra param la so , 0< page <= maxPage
        if(is_numeric($page) && $page>0 && $page<=$maxPage){
            $offset= ($page-1)*$itemPerPage;
            $data= DB::table('orders')->where('status','=',4)->where('user_id','=',$id)->offset($offset)->limit($itemPerPage)->get();
           
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'message'=> "get list order shipping successfully",
                'order' => $data,
                'perPage'=> $itemPerPage,
                'pageCurrent'=> $page,
                'maxPage' => $maxPage,
                'count'=>$count,
            ]);
        }

        return response()->json([
            'code' => config('apiconst.SERVER_ERROR'),
            'message'=>"Page not found",
            
        ]);
    }

}
