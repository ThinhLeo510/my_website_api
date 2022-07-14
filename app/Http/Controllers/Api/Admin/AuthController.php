<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // $this->middleware('api', ['except' => ['login']]);
        // $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
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

        $validator = Validator::make($request->all(), [

            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6',]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            // lấy thông tin từ các request gửi lên
            $credentials = $request->only('email', 'password');
            // if (!$token = auth()->attempt($validator->validated(), false)) {
                // $token = auth()->attempt($credentials);
                // dd($token);
            if (!$token = auth('admin-api')->attempt($credentials)) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'invalid_email_or_password',
                ], 400);
            } else {
                return response()->json([
                    'response' => 'success',
                    'token' => $token

                ], 200);
            }
        }
    }


    // dang ky
    public function register(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $admin = Admin::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if($admin){
            return response()->json([
                'message' => 'Created user successfully',
                'data' => $admin,
            ], 200);
        }else{
            return response()->json([
                'error'=>'register failed'
            ],400);
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

    // get user profile
    public function adminProfile(Request $request)
    {

            return response()->json([
                'status' => true,
                'data' => auth('admin-api')->user(),
            ], 200);

    }

    public function deleteAdmin($id){

        $admin= Admin::find($id);
        if ($admin) {
            $admin->delete();
            return response()->json([
                'message' => 'Deleted admin successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found',
            ]);
        }
    }

    public function restoreAdmin($id){
        $admin=Admin::withTrashed()->find($id);
        if($admin){
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

   
}
