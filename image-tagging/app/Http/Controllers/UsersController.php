<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \App\Models\User;
use Illuminate\Validation\Validator;



class UsersController extends Controller
{
    public function signup(Request $request){

        $validated = $request->validate([
            'name' => 'required',
            'email'    => 'unique:users|required',
            'password' => 'required',
        ]);

        $name = $request->name;
        $email    = $request->email;
        $password = $request->password;

        $user     = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
        return response()->json([
            "success" => true,
            "message" => "User created successfully",
        ]);
    }
}
