<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
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
        $this->middleware('adminAuth', ['except' => ['login', 'register']]);

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'Created user successfully',
            'admin' => $admin,
        ], 200);
    }

    // dang xuat
    public function logout()
    {
        if (auth('admin-api')->check() == false) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        auth('admin-api')->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    // get user profile
    public function adminProfile(Request $request)
    {

        if (auth('admin-api')->check()) {
            return response()->json([
                'status' => true,
                'response' => auth('admin-api')->user(),
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function findPostByAdmin($id)
    {

        if (auth('admin-api')->check()) {
            $test = Admin::find($id)->posts;
            return response()->json([
                'message' => 'Successfully logged out',
                'posts' => $test
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
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
