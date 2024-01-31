<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class UserController extends Controller
{
    function update(Request $request) {
        $token = $request->bearerToken();
        $parseJWTToken = parseJWT($token);
        $userExist = User::getUserByEmail($request->email);

        try {
            if($userExist && $request->email != $parseJWTToken->email) {
                return responseData("403", "Username already exist.");
            }
            else {
                $rules = [
                    "name" => "required|min:4",
                    "email" => "required|email",
                ];

                $validator = getValidator($request, $rules);
                if ($validator->fails()) {
                    return responseData("403", $validator->errors()->first());
                }

                $newUser = $request->only("name", "email");
                User::updateUserByEmail($parseJWTToken->email, $newUser);

                $user = User::getUserByEmail($request->email);
                $data = [
                    "token" => Auth::guard("api")->login($user)
                ];

                return responseData("200", "Your changes has been saved successfully!", $data);
            }
        }   catch (Exception $exception) {
                return responseData("500");
        }
    }
}
