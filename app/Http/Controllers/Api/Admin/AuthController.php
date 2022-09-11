<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use Exception;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use  HasFactory;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('authAdmin', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // dang nhap
    public function login(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:6',]
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'code' => config('apiconst.VALIDATE_ERROR'),
                    'error' => $validator->errors()->first()
                ]);
            } else {
                // lấy thông tin từ các request gửi lên
                $credentials = $request->only('email', 'password');
                // if (!$token = auth()->attempt($validator->validated(), false)) {
                // $token = auth()->attempt($credentials);
                // dd($token);
                if (!$token = auth('admin-api')->attempt($credentials)) {
                    return response()->json([
                        'code' => config('apiconst.INVALIED'),
                        'error' => 'invalid email or password',
                    ], 400);
                } else {
                    return response()->json([
                        'code' => config('apiconst.API_OK'),
                        'token' => $token,
                        'admin' => auth('admin-api')->user()
    
                    ], 200);
                }
            }
        } catch (Exception $e) {
            return $e;
        }
    }


    // dang ky
    public function register(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'firstname'=>['required','string','max:255','regex:/^((?!\d)[\p{L} ]+)$/u'],
            'lastname'=>['required','string','max:255','regex:/^((?!\d)[\p{L} ]+)$/u'],
            'username' => ['required', 'string', 'max:255','regex:/^((?!\d)[\p{L} ]+)$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:6'],
            'password_confirmation' => ['required','same:password']
        ],[
            'firstname.required'=> 'Vui lòng nhập tên!',
            'firstname.string'=>'Tên không đúng định dạng! Vui lòng nhập lại',
            'firstname.regex'=>'Tên không đúng định dạng! Vui lòng nhập lại',
            'lastname.required' => 'Vui lòng nhập họ và tên đệm!',
            'lastname.regex'=> 'Họ và tên đệm không đúng định dạng! Vui lòng nhập lại',
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'username.regex'=> "Tên tài khoản không đúng định định dạng! Vui lòng nhập lại.",
            'email.required'=> 'Vui lòng nhập email!',
            'email.unique' => 'Email đã tồn tại! VUi lòng chọn email khác. ',
            'email.email'=> 'Email không đúng định dạng! Vui lòng nhập lại.',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min'=> 'Mật khẩu ít nhất 6 kí tự',
            // 'password.confirmed' => 'Vui lòng nhập xác nhận mật khẩu giống với mật khẩu ban đầu.'
            'password_confirmation.same' =>'Vui lòng nhập xác nhận mật khẩu giống với mật khẩu'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => config('apiconst.VALIDATE_ERROR'),
                'error' => $validator->errors(),
            ]);
        }

        $admin = Admin::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if ($admin) {
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'data' => $admin,
            ], 200);
        } else {
            return response()->json([
                'code'=>config('apiconst.SERVER_ERROR'),
                'error' => 'register failed'
            ], 400);
        }
    }

    // dang xuat
    public function logout()
    {
        auth('admin-api')->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    public function updateAdmin(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'firstname'=>['required','string','max:255','regex:/^((?!\d)[\p{L} ]+)$/u'],
            'lastname'=>['required','string','max:255','regex:/^((?!\d)[\p{L} ]+)$/u'],
            'username' => ['required', 'string', 'max:255','unique:admins,username,'.$id.',id','regex:/^[A-Za-z0-9]+(?:[_][A-Za-z0-9]+)*$/'],

        ]);
        if ($validator->fails()) {
            return response()->json([
                'code'=>config('apiconst.VALIDATE_ERROR'),
                'error' => $validator->errors(),
            ]);
        }

        $admin = Admin::find($id);
        if ($admin) {
            $admin->lastname=$request->lastname;
            $admin->firstname=$request->firstname;
            $admin->username=$request->username;
            $admin->save();
            return response()->json([
                'code'=>config('apiconst.API_OK'),
                'message' => 'Update infor admin successfully',
                'admin'=>$admin
            ]);
        } else {
            return response()->json([
                'code'=>config('apiconst.INVALIED'),
                'message' => 'Data not found',
            ]);
        }


    }

    // get user profile
    public function adminProfile(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => auth('admin-api')->user(),
        ], 200);
    }

    public function adminProfileById($id){
        $admin = Admin::find($id);
        if ($admin) {
            return response()->json([
                'code'=>config('apiconst.API_OK'),
                'message' => 'Get infor admin successfully',
                'admin'=>$admin
            ]);
        } else {
            return response()->json([
                'code'=>config('apiconst.INVALIED'),
                'message' => 'Data not found',
            ]);
        }
    }

    public function deleteAdmin($id)
    {

        $admin = Admin::find($id);
        if ($admin) {
            $admin->delete();
            return response()->json([
                'code'=>config('apiconst.API_OK'),
                'message' => 'Deleted admin successfully',
            ], 200);
        } else {
            return response()->json([
                'code'=>config('apiconst.INVALIED'),
                'message' => 'Data not found',
            ]);
        }
    }

    public function restoreAdmin($id)
    {
        $admin = Admin::onlyTrashed()->find($id);
        if ($admin) {
            $admin->restore();
            return response()->json([
                'message' => 'Restored admin successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found',
            ]);
        }
    }

    public function resetPassword($id){
        $admin = Admin::find($id);
        if ($admin) {
            $admin->password=bcrypt(config('apiconst.DEFAULT_PASSWORD'));
            $admin->save();
            return response()->json([
                'code'=>config('apiconst.API_OK'),
                'message' => 'Reset password admin successfully',
            ], 200);
        } else {
            return response()->json([
                'code'=>config('apiconst.INVALIED'),
                'message' => 'Data not found',
            ]);
        }
    }

    public function findPostByAdmin($id)
    {
        $post = Admin::find($id)->posts;
        return response()->json([
            'message' => 'Successfully logged out',
            'data' => $post
        ], 200);
    }

    // tao moi token khi token cũ het han
    public function refresh()
    {
        return $this->createNewToken(auth('admin-api')->refresh());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin-api')->factory()->getTTL() * 60,
            'user' => auth('admin-api')->user()
        ]);
    }



    public function getListPostbyIdAdmin($id)
    {
        $post = Admin::find($id)->post;
        if ($post) {
            return response()->json([
                'data' => $post
            ], 200);
        }
    }

    public function getListAdmin()
    {   
        // $admin=Admin::paginate(4);
        // dd($admin);

        return response()->json([
            'code' => config('apiconst.API_OK'),
            'admin'=>Admin::all()
        ]);
    }

    public function listAdminPaginate($page){
        $count = DB::table('admins')->count();
        // dd($page);
        if(!isset($page)){
            $page=1;
        }
        //item tren moi trang
        $itemPerPage=5;
        //lam tron len so trang toi dang da chia
        $maxPage=ceil($count /$itemPerPage);
        ///kiem tra param la so , 0< page <= maxPage
        if(is_numeric($page) && $page>0 && $page<=$maxPage){
            $offset= ($page-1)*$itemPerPage;
            $data= DB::table('admins')->offset($offset)->limit($itemPerPage)->get();
            //loai bo password
            for($i=0;$i<count($data);$i++){
                $data[$i]->password='';
            }
            
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'admin'=>$data,
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

    public function getListUser()
    {
        return User::all();
    }
}
