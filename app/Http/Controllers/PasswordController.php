<?php

namespace App\Http\Controllers;

use App\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class PasswordController extends Controller
{
     use JsonResponseTrait;

    /**
     * Change password for authenticated user
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return $this->JsonResponse(['error' => 'User not authenticated'], 401);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->JsonResponse($validator->errors(), 422);
        }

        // تحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->JsonResponse(['current_password' => 'Current password is incorrect'], 400);
        }

        // تحديث كلمة المرور
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->JsonResponse('Password updated successfully', 200);
    }
}
