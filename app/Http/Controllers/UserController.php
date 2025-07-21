<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostsResource;
use App\JsonResponseTrait;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller{
    use JsonResponseTrait;
    // get User Profile Info 
  
}
