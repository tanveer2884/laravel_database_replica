<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect!']
            ]);
        }
        $message = 'User Logged In Successfully';
        return $this->genrateToken($user, $message);
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $message = 'User Registered Successfully';
            return $this->genrateToken($user, $message);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'email' => ['Something went wrong']
            ]);
        }
    }

    public function genrateToken($user, $message)
    {
        $token = $user->createToken('authToken')->plainTextToken;
        return AuthResource::make([
            'token' => $token,
            'message' => $message,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
        ]);
    }
}
