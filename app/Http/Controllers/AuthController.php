<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\NewUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**TODO Reefactor to use Action classes */

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (! Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success(
            [
                'user' => $user,
                'token' => $user->createToken('Api token of '.$user->first_name.' '.$user->last_name)->plainTextToken,
            ],
            'User logged in successfully.'
        );
    }

    public function register(NewUserRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'first_name' => $request->first_name,
            'other_name' => $request->other_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api token of '.$user->first_name.' '.$user->last_name)->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'User logged out successfully...', Response::HTTP_NO_CONTENT);
    }

    public function user(Request $request)
    {
        return $this->success([
            'user' => $request->user(),
        ]);
    }
}
