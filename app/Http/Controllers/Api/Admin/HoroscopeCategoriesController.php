<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HoroscopeCategory;

class HoroscopeCategoriesController extends Controller {
    public function index(Request $request)
    {
        $result = (new HoroscopeCategory)->fetch($this, $request);
        return response()->json($result); 
    }

    public function save(Request $request)
    {
        $result = (new HoroscopeCategory)->add($this, $request);
        return response()->json($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new HoroscopeCategory)->fetchFirst($this, $request, $id);
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {       
        $result = (new HoroscopeCategory)->add($this, $request, $id);
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = (new HoroscopeCategory)->remove($this, $id);
        return response()->json($result);
    }
}
