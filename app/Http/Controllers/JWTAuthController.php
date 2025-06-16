<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTAuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' =>'required|string|date|before:today',
            'gender' =>'required|string',
            'year_id'=>'required|numeric',
            'major_id' => 'numeric',
            'email' => 'required_if:mobile_number,null|email|max:255|unique:users',
            'mobile_number' => 'required_if:email,null|numeric|regex:/^09\d{8}$/|unique:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
            
            // 'bio' =>'nullable|string',
            // 'cv' => 'nullable|file|mimes:pdf',
            // 'links' => 'nullable|array',
            // 'rate' => 'numeric'
        ]);
        if($request->year_id == 4 || $request->year_id == 5) {
            if(empty($request->major_id) || $request->major_id === null){
                $response = [
                    "message" => "the specialization field required for the fourth and fifth year",
                ];
                return response()->json($response,422);
            }
        }

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }


        $user = User::create([
            "email" => $request->email !==null ? $request->email : null,
            "mobile_number" =>$request->mobile_number !==null ? $request->mobile_number : null,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'year_id' =>$request->year_id,
            'major_id' =>$request->major_id ?: null,
            
            // 
        ]);
        $token = JWTAuth::fromUser($user);
        $response = [
            "id" => $user->id,
            "message" =>"registeration done successfully",
            "token" => $token,
        ];
        return response()->json($response,201);
    }

    // User login
    public function login(Request $request)
    {


             $validation = Validator::make($request->all(),[
            "email" => 'required_if:mobile_number,null|email|string|max:255',
            "mobile_number" => 'required_if:email,null|numeric|regex:/^09\d{8}$/',
            "password" =>"required",
        ]);
        if($validation->fails()) {
            return response()->json($validation->errors(),422);
        }
         !empty($request->email) ? $credentials = $request->only('email', 'password') : $credentials = $request->only('mobile_number', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Get the authenticated user.
            $user = Auth::user();

            // (optional) Attach the role to the token.
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json(compact('token'));
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    // Get authenticated user
    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        return response()->json(compact('user'));
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }


    
}
