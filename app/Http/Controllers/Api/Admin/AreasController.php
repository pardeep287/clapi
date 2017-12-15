<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Resources\Api\Admin\AreasResource;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AreasController extends Controller
{
    public function index(Request $request)
    {
        $result = (new Area)->fetch($this, $request);
        return AreasResource::collection($result);
    }
    public function store(Request $request)
    {
        $result = (new Area)->store($this, $request);
        return new AreasResource($result);
    }
    public function show(Request $request,$id)
    {
        $result = (new Area)->fetchFirst($this, $request, $id);
        return new AreasResource($result);
    }

    public function update(Request $request, $id)
    {
        $result = (new Area)->store($this, $request, $id);
        return new AreasResource($result);
    }
    public function destroy($id)
    {
        $result = (new Area)->remove($this, $id);
        return new AreasResource($result);
    }

    public function areaCityWise(Request $request, $id)
    {
        $request->noState = true;
        $result = (new Area)->getAreaCityWise($this, $request,$id);
        return AreasResource::collection($result);
    }
}
