<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\Api\Admin\StoresResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;

class StoresController extends Controller {
    public function index(Request $request)
    {
        $result = (new Store)->fetch($this, $request);
        return StoresResource::collection($result);
    }

    public function save(Request $request)
    {
        $result = (new Store)->add($this, $request);
        return new StoresResource($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new Store)->fetchFirst($this, $request, $id);
        return new StoresResource($result);
    }

    public function update(Request $request, $id)
    {       
        $result = (new Store)->add($this, $request, $id);
        return new StoresResource($result);
    }

    public function destroy($id)
    {
        $result = (new Store)->remove($this, $id);
        return new StoresResource($result);
    }
}
