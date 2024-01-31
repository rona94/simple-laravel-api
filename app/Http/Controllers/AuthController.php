<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AuthController extends Controller
{
    function __construct() {
        $this->middleware("auth:api", ["except" => ["login", "register", "logout"]]); //"login", "register", "refresh", "logout"
    }

    function register(Request $request) {
        try {
            $rules = [
                "name" => "required|min:4",
                "email" => "required|email|unique:users,email",
                "password" => "required|confirmed|min:6|max:16",
            ];

            $validator = getValidator($request, $rules);

            if ($validator->fails()) {
                return responseData("403", $validator->errors()->first());
            }
            
            $request["password"] = Hash::make($request["password"]);
            $user = User::create($request->all());
            $token = Auth::guard("api")->login($user);
            $data = [];
            // $data = [
            //     "token" => $token
            // ];

            return responseData("200", "Your account has been created!", $data);
        } catch (Exception $exception) {
            return responseData("500");
        }
    }

    function login(Request $request) {
        try {
            $rules = [
                "email" => "required|email",
                "password" => "required"
            ];
            
            $validator = getValidator($request, $rules);

            if ($validator->fails()) {
                return responseData("403", $validator->errors()->all());
            }
            
            $user = $request->only("email", "password");
            // Auth::attempt($user, true);
            $token = Auth::guard("api")->attempt($user);

            if($token) {
                $data = [
                    "token" => $token
                ];

                return responseData("200", "Successfully logged in!", $data);
            }
            else {
                return responseData("403", "Incorrect username or password.");
            }
        } catch (Exception $exception) {
            return responseData("500");
        }
    }

    function logout(Request $request) {
        try {
            Auth::guard("api")->logout();
            
            return responseData("200", "Successfully logged out!");
        } catch (Exception $exception) {
            return responseData("500");
        }
    }
}
