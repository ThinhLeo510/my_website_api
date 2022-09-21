<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadImageController extends Controller
{
    public function uploadImageProduct(Request $request){

        
        if($request->hasFile('thumbnail')){
            $file= $request->file('thumbnail');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('public/Image'), $filename);   
            return asset('public/Image/'.$filename); 
                  
        }else{
            return asset('public/Image/none-image.png');
        }
    }

    public function uploadImagePrductAPI(Request $request){
        
        if($request->hasFile('thumbnail')){

            $validator = Validator::make($request->all(), [
            
                'thumbnail' => ['required','image'],
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'code' => config('apiconst.VALIDATE_ERROR'),
                    'error' => $validator->errors(),
                ]);
            } 
            $file= $request->file('thumbnail');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('public/Image'), $filename);   
            // return asset('public/Image/'.$filename); 
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'data' => asset('public/Image/'.$filename)
            ]);        
        }else{
            // return asset('public/Image/none-image.png');
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'data' => asset('public/Image/none-image.png')
            ]); 
        }
    }
}
