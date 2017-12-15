<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BookletMembership;

class BookletMembershipsController extends Controller {
    public function index(Request $request)
    {
        $result = (new BookletMembership)->fetch($this, $request);
        return response()->json($result); 
    }

    public function save(Request $request)
    {
        $result = (new BookletMembership)->add($this, $request);
        return response()->json($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new BookletMembership)->fetchFirst($this, $request, $id);
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $result = (new BookletMembership)->add($this, $request, $id);
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = (new BookletMembership)->remove($this, $id);
        return response()->json($result);
    }
}
