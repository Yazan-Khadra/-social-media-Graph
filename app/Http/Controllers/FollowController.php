<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // متابعة مستخدم 
    public function follow($id)
    {
        $user = Auth::user();
       
        if (!$user->followings()->where('followed_user_id', $id)->exists()) {
            $user->followings()->attach($id);
            return response()->json(['message' => 'The follow-up was successful']);
        }

        return response()->json(['message' => 'You are already following this user'], 400);
    }

    // إلغاء متابعة
    public function unfollow($id)
    {
        $user = Auth::user();

        if ($user->followings->where('followed_user_id', $id)->exists()) {
            $user->followings->detach($id);
            return response()->json(['message' => 'The follow-up has been canceled']);
        }

        return response()->json(['message' => 'You were not following this user'], 400);
    }

    // قائمة المتابعين
    public function followers()
    {
        $user = User::findOrFail(Auth::user()->id);
        $followers = $user->followers()->get();

        return response()->json($followers);
    }

    // قائمة المتابعين الذين يتابعهم المستخدم
    public function followings()
    {
        $user = User::findOrFail(Auth::user()->id);
        $followings = $user->followings()->get();

        return response()->json($followings);
    }
}

