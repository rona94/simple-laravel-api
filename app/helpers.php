<?php

use Illuminate\Support\Facades\Validator;

function responseData($statusCode, $statusMsg = "Unexpected error occurred.", $data = null) {

    $dataResponse = [];
    $statusResponse = [
        "status" => $statusCode,
        "message" => $statusMsg
    ];

    if(isset($data)) {
        $dataResponse = [
            "data" => $data
        ];
    }

    return response()->json(array_merge($statusResponse, $dataResponse));
}

function parseJWT($token) {
    $tokenParts = explode(".", $token);  
    $tokenHeader = base64_decode($tokenParts[0]);
    $tokenPayload = base64_decode($tokenParts[1]);
    $jwtHeader = json_decode($tokenHeader);
    $jwtPayload = json_decode($tokenPayload);

    return $jwtPayload;
}

function getValidator($request, $rules) {
    return Validator::make($request->all(), $rules);
}