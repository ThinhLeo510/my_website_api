<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //get list category method GET
    public function index()
    {
        return Category::all();
    }

    // show 1 category method GET
    public function show($id)
    {
        $cate = Category::find($id);
        if ($cate) {
            return response()->json([
                'data' => $cate
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found'
            ], 200);
        }
    }

    // create a new category method POST
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:category']
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors(), 200);
        }

        $cate = Category::create([
            'name' => $request->name
        ]);

        if ($cate) {
            return response()->json([
                'message' => 'Created successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Created failed'
            ]);
        }
    }

    // update category with ID , method PUT
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:category']
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors(), 200);
        }

        $cate = Category::find($id);
        if ($cate) {
            $cate->name = $request->name;
            $cate->save();
            return response()->json([
                'message' => 'Updated successfully',
                'data' => $cate
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found'
            ]);
        }
    }

    // delete a cate, method DELETE
    public function destroy($id){
        $cate= Category::find($id);
        if($cate){
            $cate->delete();
            return response()->json([
                'message'=>'Deleted successfully'
            ],200);
        }else{
            return response()->json([
                'message'=>'Data not found'
            ]);
        }
    }

    // restore a deleted cate, method PUT
    public function restore($id){
        $cate= Category::onlyTrashed()->find($id);
        if($cate){
            $cate->restore();
            return response()->json([
                'message'=>'Restored successfully'
            ],200);
        }else{
            return response()->json([
                'message'=>'Data not found'
            ]);
        }
    }

    // get list product from cate ID
    public function listProductFromCateId($id){
        $list=Category::find($id)->product;
        if($list){
            return response()->json([
                'category_id'=>$id,
                'data'=>$list
            ],200);
        }else{
            return response()->json([
                'message'=>'Data not found'
            ]);
        }
    }
}
