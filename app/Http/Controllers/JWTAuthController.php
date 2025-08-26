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
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\EmailOtp;


class JWTAuthController extends Controller
{
    use JsonResponseTrait;
    // User registration
    public function company_register(Request $request) {
        $validation = Validator::make($request->all(),[
            'company_name' => 'required|string',
            'profile_image' => 'file|mimes:png,jpg'
        ]);
        $profile_image_url = null; 
        if($request->hasFile("profile_image")){
        $path = $request->profile_image->store('profile_images','public');
       }
       $profile_image_url = '/storage/' . $path;
       Company::create([
        'id' => Auth::user()->id,
        'company_name' => $request->company_name,
        "logo_url" => $profile_image_url,
        'user_id' => Auth::user()->id
       ]);
       return $this->JsonResponse("company created succsefully",201);
    }
 public function Register_Auth(Request $request) {
    $validator = Validator::make($request->all(),[
        'email' => 'email|max:255|unique:users',
        'password' => 'required|string|min:6',
        'confirm_password' => 'required|same:password',
    ]);

    if($validator->fails()) {
        return $this->JsonResponse($validator->errors(),422);
    }

    // إنشاء المستخدم
    $user = User::create([
        "email" => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // 🔹 إنشاء OTP وإرساله بالبريد

//     $otp = rand(100000, 999999); // 6 أرقام

//  EmailOtp::create([
//         'user_id' => $user->id,
//         'otp' => $otp,
//         'expires_at' => Carbon::now()->addMinutes(10), // صلاحية 10 دقائق
//     ]);

//    Mail::raw("Your verification code is: $otp", function($message) use ($user) {
//     $message->to($user->email)
//             ->subject('Email Verification Code');
// });


    // إنشاء توكن JWT
    $token = JWTAuth::fromUser($user);

    $response = [
        "id" => $user->id,
        "message" => "registeration done successfully",
        "token" => $token,
    ];

    return $this->JsonResponse($response,201);
}

    public function Set_role(Request $request) {
        $validation = Validator::make($request->all(),[
            'role' => 'required'
        ]);
        User::where('id',Auth::user()->id)->update([
            'role' => $request->role,
        ]);
        return $this->JsonResponse("added successfully",200);
    }
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' =>'required|string|date|before:today',
            'gender' =>'required|string',
            "profile_image" => 'image|mimes:png,jpg',
            'year_id'=>'required|numeric',
            'major_id' => 'numeric',


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
          //    check if the profile image is send

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

            //
        ]);

        return response()->json("information added sucsessfully",201);
    }

    // User login
    public function login(Request $request){
             $validation = Validator::make($request->all(),[
            "email" => 'required|email|string|max:255',
            "password" =>"required",
        ]);
        if($validation->fails()) {
            return response()->json($validation->errors(),422);
        }
         $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Get the authenticated user.
            $user = Auth::user();

            // (optional) Attach the role to the token.
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);
            $respone = [
                'id' => $user->id,
                'role' => $user->role,
                'token' => $token
            ];
            return response()->json($respone);
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
