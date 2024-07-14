<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(UserCreateRequest $request)
    {
        $data = $request->validated();
        $countUser = User::where('username', $data['username'])->count();
        if ($countUser == 1) {
            return response()->json(['error' => 'Username Already Exists!'])->setStatusCode(400);
        }
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json(["success" => [
            "message" => "Registration successful!",
            "token" => $token
        ]])->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();
        $credentials = $request->only('username', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized!'])->setStatusCode(401);
        } else {
            $user = User::where('username', $request->username)->first();
            $user->token = $token;
            $user->save();
            return response()->json([
                "success" => [
                    "message" => "Login successful!",
                    "token" => $token
                ]
            ])->setStatusCode(200);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'password' => 'required|min:8'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        } else {
            User::where('id', $user->id)->update(['password' => $request->password]);
            return response()->json([
                'success' => [
                    'message' => 'Passwords updated successfully!'
                ]
            ]);
        }
    }

    public function logout()
    {
        User::where('username', auth()->user()->username)->update(['token' => null]);
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json(Auth::user());
    }
}
