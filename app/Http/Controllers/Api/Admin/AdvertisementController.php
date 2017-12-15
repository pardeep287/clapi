<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdvertisementController extends Controller {


    public function index(Request $request,$page=1)
    {
        $result = [];
        $perPage = 20;
        $start = ($page - 1) * $perPage;
        $result = (new Advertisement)->getAdvertisement([],$start, $perPage,true);
        return response()->json($result);
    }

    public function randomAdvertisement()
    {
        $result =  [];
        $result = (new Advertisement)->getRandomAdvertisement();
        return response()->json($result);
    }

    public function save(Request $request)
    {
        $result = (new Country)->add($this, $request);
        return response()->json($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new Country)->fetchFirst($this, $request, $id);
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {       
        $result = (new Country)->add($this, $request, $id);
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = (new Country)->remove($this, $id);
        return response()->json($result);
    }
}
