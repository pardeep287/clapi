<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\Api\Admin\CountriesResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;

class CountriesController extends Controller {
    public function index(Request $request)
    {
        $result = (new Country)->fetch($this, $request);
        return CountriesResource::collection($result);
    }

    public function store(Request $request)
    {
        $result = (new Country)->add($this, $request);
        return new CountriesResource($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new Country)->fetchFirst($this, $request, $id);
        return new CountriesResource($result);
    }

    public function update(Request $request, $id)
    {
        $result = (new Country)->add($this, $request, $id);
        return new CountriesResource($result);
    }

    public function destroy($id)
    {
        $result = (new Country)->remove($this, $id);
        return new CountriesResource($result);
    }

}
