<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(NewUserRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'first_name' => $request->first_name,
            'other_name' => $request->other_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api token of '. $user->first_name. ' ' . $request->last_name)->plainTextToken
        ]);
    }
}
