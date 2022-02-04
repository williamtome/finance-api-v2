<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(UserRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];

        if (!Auth::attempt($credentials)) {
            return $this->error(401, 'As credenciais estão incorretas.');
        }

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);
    }
}
