<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\Api\Admin\CitiesResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;

class CitiesController extends Controller {
    public function index(Request $request)
    {
        $result = (new City)->fetch($this, $request);
        return CitiesResource::collection($result);
    }

    public function store(Request $request)
    {
        $result = (new City)->add($this, $request);
        return new CitiesResource($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new City)->fetchFirst($this, $request, $id);
        return new CitiesResource($result);
    }

    public function update(Request $request, $id)
    {       
        $result = (new City)->add($this, $request, $id);
        return new CitiesResource($result);
    }

    public function destroy($id)
    {
        $result = (new City)->remove($this, $id);
        return new CitiesResource($result);
    }
    public function CityStateWise(Request $request, $id)
    {
        $request->noState = true;
        $result = (new City)->getCityStateWise($this, $request,$id);
        return CitiesResource::collection($result);
    }
}
