<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BookletMembershipDeal;
use App\Http\Resources\Api\BookletDealsResource;
use App\Http\Resources\Api\BookletMembershipsResource;

class BookletMembershipDealsController extends Controller {
    public function index(Request $request, $bookletId)
    {
        $result = (new BookletMembershipDeal)->fetch($this, $request, $bookletId);
        return BookletDealsResource::collection($result);
    }

    public function show(Request $request, $bookletId, $dealId)
    {
        $result = (new BookletMembershipDeal)
            ->fetchFirst($this, $request, $bookletId, $dealId);
        return new BookletDealsResource($result);
    }
}
