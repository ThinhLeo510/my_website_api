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

    public function login(Request $request){
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
            if (!$token = auth('user-api')->attempt($credentials)) {
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
            'firstname'=>['required','string','max:255','regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/'],
            'lastname'=>['required','string','max:255','regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/'],
            'gender' => ['required', 'numeric', 'min:1'],
            'address' => ['required', 'string'],
            'phone'=>['required','string','regex:/(0)[0-9]{9}/','unique:users'],
            'username' => ['required', 'string', 'max:255','unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'address'=>$request->address,
            'phone'=>$request->phone,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if ($user) {
            return response()->json([
                'message' => 'Created user successfully',
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'error' => 'register failed'
            ], 400);
        }
    }

    public function logout()
    {

        auth('user-api')->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    // get user profile
    public function userProfile(Request $request)
    {
        return response()->json([
            'status' => true,
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
        $user=User::find($id);
        if($user){
            return response()->json([
                'data'=>$user
            ],200);
        }else{
            return response()->json([
                'message'=>'Data not found'
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
    public function update(Request $request, User $user)
    {
        //
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
