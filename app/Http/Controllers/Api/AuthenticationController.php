<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthenticationController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {

        $validatedData = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:8'
        ]);


        if (!Auth::attempt($validatedData)) {
            return $this->error(401, '', 'credentials error');
        }

        $user = User::all()->where('email', $validatedData['email'])->first();

        if (!Hash::check($validatedData['password'], $user->password, [])) {
            throw new Exception('Error in Login');
        }

        return $this->success([
            'token' => $user->createToken('authToken')->plainTextToken,
            'user' => UserResource::make($user)
        ], 'user successfully logged in', 201);


    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return $this->success('', 'Token successfully deleted', 200);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::create($validatedData);
        $token = $user->createToken('appToken')->plainTextToken;

        return $this->success(['user' => UserResource::make($user), 'token' => $token], 'user successfully created', 201);
    }
}
