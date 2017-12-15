<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {
    public function index(Request $request)
    {
        $result = (new User)->fetch($this, $request);
        return response()->json($result); 
    }

    public function save(Request $request)
    {
        $result = (new User)->add($this, $request);
        return response()->json($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new User)->fetchFirst($this, $request, $id);
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {       
        $result = (new User)->add($this, $request, $id);
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = (new User)->remove($this, $id);
        return response()->json($result);
    }
}
