<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($request->all())) {
            return response()->json(['message' => 'User not exist'], 404);
        }

        return $this->me(true);
    }

    public function me($creatToken = false)
    {
        $user = auth()->user();

        if ($creatToken) {
            return $user->createToken('')->plainTextToken;
        }

        return $user;
    }

    public function logout()
    {
        $user = auth()->user();

        $user->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'name' => 'required'
        ]);

        return User::query()->create($request->all());
    }
}
