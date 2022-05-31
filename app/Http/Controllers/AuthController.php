<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;

class AuthController extends Controller
{
    public function register(UserRequest $request): AuthResource
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return AuthResource::make([
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
}
