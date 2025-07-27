<?php

namespace App\Http\Controllers;

use App\JsonResponseTrait;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;



class JWTAuthController extends Controller
{
    use JsonResponseTrait;
    // User registration
    public function Register_Auth(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required_if:mobile_number,null|email|max:255|unique:users',
            'mobile_number' => 'required_if:email,null|numeric|regex:/^09\d{8}$/|unique:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
        ]);
        if($validator->fails()) {
            return $this->JsonResponse($validator->errors(),422);
        }
        $user= User::create([
            "email" => $request->email !==null ? $request->email : null,
            "mobile_number" =>$request->mobile_number !==null ? $request->mobile_number : null,
            'password' => Hash::make($request->password),
        ]);
        $token = JWTAuth::fromUser($user);
        $response = [
            "id" => $user->id,
            "message" =>"registration done successfully",
            "token" => $token,
        ];
        return $this->JsonResponse($response,201);

    }
    //user register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // user info
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' =>'required|string|date|before:today',
            'gender' =>'required|string',
            "profile_image" => 'image|mimes:png,jpg',
            'year_id'=>'required|numeric',
            'major_id' => 'numeric',
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

        //check if the profile image is send

        if($request->hasFile("profile_image")){
        $path = $request->profile_image->store('profile_images','public');
        }
        $profile_image_url = '/storage/' . $path;

        $user = User::where('id',Auth::user()->id)->update([

            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'profile_image_url' =>$profile_image_url,
            'gender' => $request->gender,
            'year_id' =>$request->year_id,
            'major_id' =>$request->major_id ?: null,
        ]);
        $token = JWTAuth::fromUser($user);
        $response = [
            "id" => $user->id,
            "message" =>"registeration done successfully",
            "token" => $token,
        ];
        return response()->json($response,201);
    }
    //company register
        public function company_register(Request $request){
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string',
            ]);

            if($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Get the authenticated user
            $user = Auth::user();

            // Update user role to company
            $user = User::where('id', Auth::user()->id)->update([
                "role" => "company"
            ]);
            $user = Auth::user();

            // Create or update company record with company_name
            Company::updateOrCreate(
                ['email' => Auth::user()->email,
                'mobile_number' => Auth::user()->mobile_number],
                [
                    'company_name' => $request->company_name,
                    'description' => null,
                    'logo_url' => null,
                ]
            );

            $token = JWTAuth::fromUser($user);
            $response = [
                "id" => $user->id,
                "message" => "Company registration done successfully",
                "token" => $token,
                "role" => "company"
            ];
            return response()->json($response, 201);
        }

    //academic register
    public function academic_register(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
            'birth_date' => 'required|date|before:today',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Update user role to academic
        User::where('id', $user->id)->update([
            "role" => "academic"
        ]);
        $user = Auth::user();

        // Create or update academic staff record
        AcademicStaff::updateOrCreate(
            [
                'email' => $user->email,
                'mobile_number' => $user->mobile_number
            ],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
            ]
        );

        $token = JWTAuth::fromUser($user);
        $response = [
            "id" => $user->id,
            "message" => "Academic staff registration done successfully",
            "token" => $token,
            "role" => "academic"
        ];
        return response()->json($response, 201);
    }


    // User login
    public function login(Request $request){
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
