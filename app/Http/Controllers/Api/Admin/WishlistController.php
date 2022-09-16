<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helper\Helper;
use App\Models\User;
use App\Models\Wishlist;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\TextUI\Help;

class WishlistController extends Controller
{
    //get wishlist
    public function index($user_id){
        try{
            $wishList= User::find($user_id)->wishlist;
            if(Count($wishList)==0){
                return response()->json([
                    'code' => config('apiconst.DATA_EMPTY'),
                    'message' => 'Data empty'
                ]);
            }else{
                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'message' => 'Get wishlist successfully',
                    'data' => $wishList
                ]);
            }
        }catch( Exception $e){
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message' => 'ERROR: '.$e
            ]);
        }
    }

    //add to wishlist wuth product_id
    public function addWishlist(Request $request){
        try{

            $validator = Validator::make($request->all(),[
                'user_id' => ['required','numeric','exists:users,id'],
                'product_id'=>['required','numeric','exists:products,id','unique:wishlist']
            ]);

            if($validator->fails()){
               return Helper::responseValidate($validator->errors());
            }

            // $wishList= DB::table('wishlist')->where('user_id',$request->user_id)->insert([
            //     'user_id' => $request->user_id,
            //     'product_id' => $request->product_id
            // ]);

            $wishList= Wishlist::create([
                'user_id'=> $request->user_id,
                'product_id' => $request->product_id
            ]);

            if($wishList){
                return Helper::responseData(config('apiconst.API_OK'),'Add product to wishlist successfully',$wishList);
            }else{
                return Helper::responseData(config('apiconst.INVALIED'),'Add product to wishlist failed');
            }

        }catch(Exception $e){
            return Helper::responseData(config('apiconst.SERVER_ERROR'),''.$e);
        }
    }

    // remove product form wishlist
    public function removeWishlist($id){
        try {
            
            $wishList = Wishlist::find($id)->delete();
            if($wishList){
                return Helper::responseData(config('apiconst.API_OK'),'Deleted product from wishlist successfully');
            }else{
                return Helper::responseData(config('apiconst.INVALIED'),'Deleted product from wishlist fail');
            }

        } catch (Exception $e) {
            return Helper::responseData(config('apiconst.SERVER_ERROR'),''.$e);
        }
    }
    
}
