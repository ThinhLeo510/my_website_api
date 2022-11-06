<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get list user
        return User::all();
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => config('apiconst.VALIDATE_ERROR'),
                'error' => $validator->errors()
            ]);
        }
        else {
            // lấy thông tin từ các request gửi lên
            $credentials = $request->only('email', 'password');
            // if (!$token = auth()->attempt($validator->validated(), false)) {
            // $token = auth()->attempt($credentials);
            // dd($token);
            if (!$token = auth('user-api')->attempt($credentials)) {
                return response()->json([
                    'code' => config('apiconst.INVALIED'),
                    'error' => 'Sai email hoặc mật khẩu. Vui lòng thử lại!',
                ], 400);
            }
            else {
                return response()->json([
                    'code' => config('apiconst.API_OK'),
                    'response' => 'success',
                    'token' => $token,
                    'user' => auth('user-api')->user()

                ], 200);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        //create a new user
        // validate
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255', 'regex:/^((?!\d)[\p{L} ]+)$/u'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^((?!\d)[\p{L} ]+)$/u'],
            'username' => ['required', 'string', 'max:255', 'unique:users', 'regex:/^[A-Za-z0-9]+(?:[_][A-Za-z0-9]+)*$/'],
            'gender' => ['required', 'numeric', 'min:1'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'regex:/(0)[0-9]{9}/', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => config('apiconst.VALIDATE_ERROR'),
                'error' => $validator->errors()->first()
            ]);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone' => $request->phone,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if ($user) {
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'message' => 'Created user successfully',
                'data' => $user,
            ], 200);
        }
        else {
            return response()->json([
                'code' => config('apiconst.SERVER_ERROR'),
                'error' => 'register failed'
            ], 400);
        }
    }

    public function logout()
    {

        auth('user-api')->logout();
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ], 200);
    }

    // get user profile
    public function userProfile()
    {
        return response()->json([
            'code' => config('apiconst.API_OK'),
            'data' => auth('user-api')->user(),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::find($id);
        if ($user) {
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'data' => $user
            ], 200);
        }
        else {
            return response()->json([
                'code' => config('apiconst.DATA_EMPTY'),
                'message' => 'Data not found'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255', 'regex:/^((?!\d)[\p{L} ]+)$/u'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^((?!\d)[\p{L} ]+)$/u'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $id . ',id', 'regex:/^[A-Za-z0-9]+(?:[_][A-Za-z0-9]+)*$/'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:10', 'regex:/(0)[0-9]{9}/', 'unique:users,phone,' . $id . ',id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id . ',id'],

        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => config('apiconst.VALIDATE_ERROR'),
                'error' => $validator->errors(),
            ]);
        }

        $user = User::find($id);
        if ($user) {
            $user->lastname = $request->lastname;
            $user->firstname = $request->firstname;
            $user->username = $request->username;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
            return response()->json([
                'code' => config('apiconst.API_OK'),
                'message' => 'Update infor user successfully',
                'user' => $user
            ]);
        }
        else {
            return response()->json([
                'code' => config('apiconst.INVALIED'),
                'message' => 'Data not found',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
    //
    }
}