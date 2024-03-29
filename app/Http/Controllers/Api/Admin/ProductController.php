<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UploadImageController;
use App\Models\Category;
use App\Models\Product;
use Exception;
use GuzzleHttp\RetryMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Framework\Constraint\Count;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    // update product slug all
    public function slug()
    {
        $list = Product::all();
        foreach ($list as $key => $product) {
            // dd($product->name);
            $product->slug = Str::slug($product->name);
            $product->save();
        }
    }

    // get list product trendy
    public function getListProductTrendy(){
        try{
            $list= Product::orderBy('updated_at','desc')->paginate(12);
            if(!empty($list)){
                return Helper::responseData(config('apiconst.API_OK'),config('apiconst.MESS_OK'),$list);
            }

        }catch(Exception $e){
            return Helper::responseData(config('apiconst.SERVER_ERROR'),''.$e) ;
        }
    }

    public function listProducPaginate($page)
    {
        try {
            $count = DB::table('products')->count();
            if (!isset($page)) {
                $page = 1;
            }
            // dd($page);

            //item tren moi trang
            $itemPerPage = 12;
            //lam tron len so trang toi dang da chia
            $maxPage = ceil($count / $itemPerPage);
            ///kiem tra param la so , 0< page <= maxPage
            if (is_numeric($page) && $page > 0 && $page <= $maxPage) {
                $offset = ($page - 1) * $itemPerPage;
                $data = DB::table('products')->offset($offset)->limit($itemPerPage)->get();

                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'data' => $data,
                    'perPage' => $itemPerPage,
                    'pageCurrent' => $page,
                    'maxPage' => $maxPage,
                    'count' => $count,

                ]);
            }

            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message' => "Page not found",
                // 'count'=>$count,

            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message' => '' . $e,

            ]);
        }
    }

    // test list product paginate
    public function getListProduct()
    {
        try {
            $list = Product::paginate(5);
            if (Count($list) > 0) {
                return Helper::responseData(config('apiconst.API_OK'), config('apiconst.MESS_OK'), $list);
            } else if (Count($list) == 0) {
                return Helper::responseData(config('apiconst.DATA_EMPTY'), config('apiconst.MESS_DATA_EMPTY'));
            }else{
                return Helper::responseData(config('apiconst.INVALIED'), config('apiconst.MESS_NOT_FOUND'));
            }
        } catch (Exception $e) {
            return Helper::responseData(config('apiconst.SERVER_ERROR'), '' . $e);
        }
    }

    //get list product of category ID
    public function listProductCate($cate_id, $page)
    {
        try {
            $count = DB::table('products')->where('category_id', $cate_id)->count();
            if (!isset($page)) {
                $page = 1;
            }

            if ($count === 0) {
                return response()->json([
                    'code' => config('apiconst.DATA_EMPTY'),
                    'message' => "Danh mục chưa có sản phẩm"
                ]);
            } else {

                //item tren moi trang
                $itemPerPage = 12;
                //lam tron len so trang toi dang da chia
                $maxPage = ceil($count / $itemPerPage);
                ///kiem tra param la so , 0< page <= maxPage
                if (is_numeric($page) && $page > 0 && $page <= $maxPage) {
                    $offset = ($page - 1) * $itemPerPage;
                    $data = DB::table('products')->where('category_id', $cate_id)->offset($offset)->limit($itemPerPage)->get();
                    $cate = Category::find($cate_id);

                    // dd(json_decode($data));
                    return response()->json([
                        'code' => config('apiconst.API_OK'),
                        'category_id' => $cate_id,
                        'category_name' => json_decode($cate)->name,
                        'data' => $data,
                        'perPage' => $itemPerPage,
                        'pageCurrent' => $page,
                        'maxPage' => $maxPage,
                        'count' => $count,
                    ]);
                }

                return response()->json([
                    'code' => config('apiconst.SERVER_ERROR'),
                    'message' => "Page not found",
                    // 'count'=>$count,

                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message' => '' . $e,

            ]);
        }
    }

    // get list product in category
    public function getListProductCategory(){
        try {
            dd(1);
        } catch (Exception $e) {
            return Helper::responseData(config('apiconst.SERVER_ERROR'), '' . $e);
        }
    }

    // show 1 product detail
    public function show($idOrSlug)
    {
        if(is_numeric($idOrSlug)){
            $product = Product::find($idOrSlug);
        }else if(is_string($idOrSlug)){
            $product= Product::where('slug',$idOrSlug)->first();
        }else{
            return Helper::responseData(config('apiconst.INVALIED'),config('apiconst.MESS_INVALIED_PARAM'));
        }

        if ($product) {
            return Helper::responseData(config('apiconst.API_OK'),config('apiconst.MESS_OK'),$product);
        } else {
            return Helper::responseData(config('apiconst.DATA_EMPTY'),config('apiconst.MESS_DATA_EMPTY'));
        }
    }

    //get related product
    public function relatedProduct($id)
    {
        try {
            $product = Product::find($id);
            $cate = $product->category_id;
            $related_products = DB::table('products')->where('category_id', $cate)->where('id', '!=', $product->id)->offset(0)->limit(4)->get();
            if ($related_products) {
                return Helper::responseData(config('apiconst.API_OK'),config('apiconst.MESS_OK'),$related_products);
            } else {
                return response()->json([
                    'code' => config('apiconst.INVALIED'),
                    'message' => 'Get related product failed',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'message' => '' . $e,
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:products'],
            'category_id' => ['required', 'exists:category,id'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'discount_price' => ['numeric', 'nullable'],
            'thumbnail' => ['required', 'image'],
            'discount_id' => ['numeric', 'nullable']
        ]);

        // dd($request->all());

        if ($validator->fails()) {
            return response()->json([
                'code' => config('apiconst.VALIDATE_ERROR'),
                'error' => $validator->errors(),
            ]);
        }

        $uploadImage = new UploadImageController();

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'discount_price' => $request->discount_price,
            'thumbnail' => $uploadImage->uploadImagePrduct($request),
            'discount_id' => $request->discount_id
        ]);

        if ($product) {
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'message' => 'Created successfully',
                'data' => $product,
            ], 200);
        } else {
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'error' => 'Create failed'
            ], 400);
        }
    }

    // update product
    public function update(Request $request, $id)
    {

        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $id . ',id'],
            'category_id' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'discount_price' => ['numeric', 'nullable'],
            'thumbnail' => ['required', 'mimes:jpeg,jpg,png,gif',],
            'discount_id' => ['numeric', 'nullable']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::find($id);
        // dd($product);

        if ($product) {

            $uploadImage = new UploadImageController();

            try {

                $product->name = $request->name;
                $product->category_id = $request->category_id;
                $product->price = $request->price;
                $product->quantity = $request->quantity;
                $product->discount_price = $request->discount_price;
                $product->thumbnail = $uploadImage->uploadImagePrduct($request);
                $product->discount_id = $request->discount_id;
                $product->save();
                // dd($product);
            } catch (Exception $e) {
                return response()->json([
                    'code' => config('apiconst.SERVER_ERROR'),
                    'message' => '' . $e
                ]);
            }

            return response()->json([
                'code' => config('apiconst.API_OK'),
                'message' => 'Updated successfully',
                'data' => $product,
            ], 200);
        } else {
            return response()->json([
                'code' => config('apiconst.INVALIED'),
                'message' => 'Data not found'
            ]);
        }
    }

    public function destroy($id)
    {

        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'message' => 'Deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found'
            ]);
        }
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if ($product) {
            $product->restore();
            return response()->json([
                'message' => 'Restored successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found'
            ]);
        }
    }

    public function getListProductDeleted()
    {
        $product = Product::onlyTrashed()->get();
        if ($product) {
            return response()->json([
                'data' => $product
            ], 200);
        } else {
            return response()->json([
                'data' => 'nothing to show'
            ]);
        }
    }
}
