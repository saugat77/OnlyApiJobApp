<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        $input = $request->all();
        $validator =  Validator::make($input,[
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        if(Auth::attempt([
            "email" => $request->email,
            "password" => $request->password,
        ])){
                //User Exists
                $user = Auth::user();
                $token = $user->createToken('loginToken')->accessToken;
                return response()->json([
                    'result' =>  $token,
                    'status' => True,
                    'message' => 'Logged In Successfully'
                ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Invalid login details'
            ]);
        }
    }
    public function register(Request $request){
        $input = $request->all();
        $validator =  Validator::make($input,[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'user_type' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
         $user =  User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'user_type' => $request->user_type
        ]);
        $token = $user->createToken('Token')->accessToken;
            return response()->json([
                'result' =>$user,
                'message' => 'Registered Successfully',
                'status'  => true,
            ]);
    }
    public function logout(Request $request){
        Auth::user()->token()->revoke();
        return response()->json([
            'status' => true,
            'message' => 'User Logged out',
        ]);
    }
    public function sendError($message) {
        $message = $message->all();
        $response['error'] = "validation_error";
        $response['message'] = implode('',$message);
        $response['status'] = "0";
        return response()->json($response, 200);
    }
}
