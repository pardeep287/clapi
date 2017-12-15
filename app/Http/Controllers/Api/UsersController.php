<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;

class UsersController extends Controller {
    public function save(Request $request)
    {
        $result = (new User)->add($this, $request);
        return new UserResource($result);
    }
}
