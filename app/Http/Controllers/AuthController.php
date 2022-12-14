<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(UserLoginRequest $request)
    {

        $request->validated($request->all());

        $user = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!Auth::attempt($user)) {
            // dd("test");
            return $this->error('', 'Crendetials does not match', 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api Token of ' . $user->email)->plainTextToken
        ]);
    }

    public function register(UserFormRequest $request)
    {

        // dd($request->all());
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->input('email'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->email)->plainTextToken
        ]);
    }

    public function logout()
    {
        return response()->json('This is my logout Method');
    }
}
