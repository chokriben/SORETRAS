<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Http\Requests\StoreUserRequest;

class AuthController extends Controller

{
    use HttpResponses;


    public function login(LoginUserRequest $request)
    {
        $request->validated($request->only(['email', 'password']));

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
    public function register(StoreUserRequest $request)
    {
        $csrf = $request->header('Csrf_token');
        $csrftoken = $request->input('csrf_token');

        if ($csrftoken == $csrf) {
            if (!Auth::check()) {
                return $this->error('', 'Unauthorized', 401);
            }
            $request->validated($request->only(['name', 'email', 'password']));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'gare_id' => $request->gare_id,
            ]);

            return $this->success([
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken
            ]);
        } else {
            return $this->success([
                'error' => "unknown status",
                //'token' => $user->createToken('API Token')->plainTextToken
            ]);
        }
    }


    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'Vous avez été déconnecté avec succès'
        ]);
    }
}
