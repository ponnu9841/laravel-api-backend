<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function user(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $response = [
                'status' => true,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo,
                'role' => $user->usertype
            ];
            return response($response, 200);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 200);
        }
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:55',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response([
                'status' => true,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->usertype
            ], 200);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function login(Request $request)
    {
        $validators = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validators->passes()) {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response([
                    'mesage' => "Invalid Credentials"
                ], 203);
            }

            $user = Auth::user();

            $token = $user->createToken('token')->plainTextToken;

            $cookie = Cookie::make('jwt', $token, 60 * 24); // 1 day

            // return response([
            //     'message' => 'sucess',
            //     'name' => $user->name,
            //     'email' => $user->email,
            //     'role' => $user->role,
            //     'token' => $token
            // ]);

            return response([
                'status' => true,
                'message' => 'sucess',
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->usertype,
                'token' => $token
            ])->withCookie($cookie)->withHeaders([
                'SameSite' => 'None',
                // 'Secure' => true, // Only if your application is served over HTTPS
            ]);;
        } else {
            return response([
                'status' => false,
                'errors' => $validators->errors()
            ]);
        }
    }

    public function logout(Request $request)
    {

        $cookie = Cookie::forget('jwt');

        $user = $request->user();

        if (!empty($user)) {
            $user->currentAccessToken()->delete();

            return response([
                'status' => true,
                'message' => 'success'
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'unauthorized'
            ], 203);
        }







        // dd($user);
        // exit();

        // return response([
        //     'message' => 'success'
        // ], 200)->withCookie(($cookie));
    }
}
