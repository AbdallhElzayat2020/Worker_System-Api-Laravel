<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class WorkerController extends Controller implements JWTSubject
{


    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register', 'refresh', 'logout', 'test']]);
    }

    public function test()
    {
        $admins = Worker::all();
        return response()->json($admins);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:workers',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:17',
            'address' => 'required|string|max:200'
        ]);

        $user = Worker::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $token = Auth::guard('worker')->login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::guard('worker')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('worker')->user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::guard('worker')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::guard('worker')->user(),
            'authorisation' => [
                'token' => Auth::guard('worker')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Assuming this is a model with a primary key
    }

    public function getJWTCustomClaims()
    {
        return []; // Add any custom claims here
    }
}
