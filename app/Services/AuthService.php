<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * handle the register logic
     */
    public function register(array $data):User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        return $user;
    }

    /**
     * handle the login logic
     */
    public function login(array $data):?string
    {
        $user = User::where('email',$data['email'])->first();

        if(!$user || !Hash::check($data['password'],$user->password)){
            return null;
        }
        return $user->createToken('api_token')->plainTextToken;
    }

    /**
     * handle the logout logic
     */
    public function logout($user): void
    {
        $user->tokens()->delete();
    }
}
