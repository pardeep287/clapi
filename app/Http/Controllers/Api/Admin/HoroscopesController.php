<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Horoscope;

class HoroscopesController extends Controller {
    public function index(Request $request)
    {
        $result = (new Horoscope)->fetch($this, $request);
        return response()->json($result);
    }
    
    public function byCategoryId(Request $request, $categoryId)
    {
        $result = (new Horoscope)->byCategoryId($this, $request, $categoryId);
        return response()->json($result);
    }

    public function save(Request $request)
    {
        $result = (new Horoscope)->add($this, $request);
        return response()->json($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new Horoscope)->fetchFirst($this, $request, $id);
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {       
        $result = (new Horoscope)->add($this, $request, $id);
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = (new Horoscope)->remove($this, $id);
        return response()->json($result);
    }
}
