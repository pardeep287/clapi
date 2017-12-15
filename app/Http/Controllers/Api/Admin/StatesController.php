<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\Api\Admin\StatesResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State;



class StatesController extends Controller {
    public function index(Request $request)
    {
        $result = (new State)->fetch($this, $request);
        return StatesResource::collection($result);
    }

    public function store(Request $request)
    {
        $result = (new State)->add($this, $request);
        return new StatesResource($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new State)->fetchFirst($this, $request, $id);
        return new StatesResource($result);
    }

    public function update(Request $request, $id)
    {       
        $result = (new State)->add($this, $request, $id);
        return new StatesResource($result);
    }

    public function destroy($id)
    {
        $result = (new State)->remove($this, $id);
        return new StatesResource($result);
    }
    public function StateCountriesWise(Request $request, $id)
    {
        $request->noCountry = true;
        $result = (new State)->getStateCountriesWise($this, $request,$id);
        return StatesResource::collection($result);
    }
}
