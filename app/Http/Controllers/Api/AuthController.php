<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\UserEditResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Http\Resources\Api\UserVerificationResource;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{
    /**
     * Undocumented function
     */
    public function __construct() { }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function authenticate(Request $request) 
    {
        $result = (new User)->check($this, $request);
        return new UserResource($result);
    }


    public function appAuthenticate(Request $request) 
    {
        $result = (new User)->appAuthenticate($this, $request);
        return new UserResource($result);
    }

    public function verification(Request $request) 
    {
        $result = (new User)->verification($this, $request);
        return new UserVerificationResource($result);
    }

    public function editAppAuth(Request $request)
    {
        $result = (new User)->editDetailsAuth($this, $request);
        return new UserResource($result);
    }

    public function getJbCoins(Request $request)
    {
        $result = (new User)->getJbCoins($this, $request);
        return new UserResource($result);
    }
}
