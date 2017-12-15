<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\BookletMembership;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BookletMembershipsResource;

class BookletMembershipsController extends Controller {
    public function index(Request $request)
    {
        $result = (new BookletMembership)->fetch($this, $request);
        return BookletMembershipsResource::collection($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new BookletMembership)->fetchFirst($this, $request, $id);
        return new BookletMembershipsResource($result);
    }
}
