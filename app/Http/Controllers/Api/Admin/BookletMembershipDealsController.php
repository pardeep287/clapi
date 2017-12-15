<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BookletMembershipDeal;

class BookletMembershipDealsController extends Controller {
    public function index(Request $request, $bookletMembershipId)
    {
        $request->booklet_membership_id = $bookletMembershipId;
        $result = (new BookletMembershipDeal)->fetch($this, $request, $bookletMembershipId);
        return response()->json($result); 
    }

    public function save(Request $request, $bookletMembershipId)
    {
        $request->booklet_membership_id = $bookletMembershipId;
        $result = (new BookletMembershipDeal)->add($this, $request);
        return response()->json($result);
    }

    public function show(Request $request, $bookletMembershipId, $id)
    {
        $request->booklet_membership_id = $bookletMembershipId;
        $result = (new BookletMembershipDeal)->fetchFirst($this, $request, $id);
        return response()->json($result);
    }

    public function update(Request $request, $bookletMembershipId, $id)
    {       
        $request->booklet_membership_id = $bookletMembershipId;
        $result = (new BookletMembershipDeal)->add($this, $request, $id);
        return response()->json($result);
    }

    public function destroy($bookletMembershipId, $id)
    {
        $result = (new BookletMembershipDeal)->remove($this, $bookletMembershipId, $id);
        return response()->json($result);
    }
}
